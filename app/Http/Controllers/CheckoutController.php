<?php

namespace App\Http\Controllers;

use App\Actions\Orders\CreateOrderAction;
use App\DTO\CreateOrderPayload;
use App\DTO\OrderLineDraftDto;
use App\Models\City;
use App\Models\Order;
use App\Support\StoreCart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CheckoutController extends Controller
{
    public function store(Request $request, CreateOrderAction $createOrder): RedirectResponse
    {
        if ($request->session()->get('checkout_in_flight')) {
            return redirect()
                ->route('store.cart')
                ->withErrors(['cart' => __('aldawy.checkout_duplicate_or_expired')]);
        }

        $data = $request->validate([
            'checkout_nonce' => ['required', 'string', 'size:40'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'shipping_address_line1' => ['required', 'string', 'max:255'],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:32'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $sessionNonce = $request->session()->pull('checkout_nonce');
        if (
            ! is_string($sessionNonce)
            || ! hash_equals($sessionNonce, $data['checkout_nonce'])
        ) {
            return redirect()
                ->route('store.cart')
                ->withErrors(['cart' => __('aldawy.checkout_duplicate_or_expired')])
                ->withInput();
        }

        $city = City::query()->whereKey((int) $data['city_id'])->where('is_active', true)->first();
        if ($city === null) {
            return redirect()
                ->route('store.cart')
                ->withErrors(['city_id' => __('aldawy.checkout_city_invalid')])
                ->withInput();
        }

        $rows = StoreCart::resolved();
        if ($rows->isEmpty()) {
            return redirect()
                ->route('store.cart')
                ->withErrors(['cart' => __('aldawy.checkout_cart_empty')]);
        }

        if (StoreCart::hasDroppedLines()) {
            return redirect()
                ->route('store.cart')
                ->withErrors(['cart' => __('aldawy.checkout_cart_changed')]);
        }

        $request->session()->put('checkout_in_flight', true);

        $draftLines = [];
        foreach ($rows as $row) {
            /** @var \App\Models\Product $product */
            $product = $row['product'];
            $servicesMap = [];
            foreach ($row['preparation_services'] as $ps) {
                $servicesMap[$ps->code] = true;
            }
            $pkg = $row['packaging_type'];
            $packagingCode = $pkg ? (string) $pkg->code : '';

            $extras = bcadd($row['services_surcharge'], $row['packaging_surcharge'], 4);

            $draftLines[] = new OrderLineDraftDto(
                productId: $product->id,
                produceBoxId: null,
                productNameSnapshot: $product->getTranslations('name'),
                unit: 'kg',
                quantity: $row['kg'],
                services: $servicesMap,
                packaging: $packagingCode,
                unitPrice: (string) $product->price_per_kg,
                servicesSurchargeTotal: $extras,
            );
        }

        $email = $data['customer_email'] ?? null;
        if ($email === null || $email === '') {
            $email = $request->user()?->email;
        }

        $payload = new CreateOrderPayload(
            userId: $request->user()?->id,
            cityId: (int) $data['city_id'],
            shippingAddressLine1: $data['shipping_address_line1'],
            shippingAddressLine2: $data['shipping_address_line2'] ?? null,
            customerPhone: $data['customer_phone'],
            customerName: $data['customer_name'] ?? null,
            customerEmail: $email,
            packagingCode: '',
            packagingFee: '0',
            lines: $draftLines,
            notes: $data['notes'] ?? null,
        );

        try {
            $order = $createOrder->execute($payload);
        } finally {
            $request->session()->forget('checkout_in_flight');
        }

        StoreCart::clear();

        $request->session()->put('checkout_order_id', $order->id);

        return redirect()->route('store.checkout.thanks');
    }

    public function thanks(Request $request): View|RedirectResponse
    {
        $id = $request->session()->pull('checkout_order_id');
        if (! $id) {
            return redirect()->route('store.shop')->with('status', __('aldawy.checkout_session_expired'));
        }

        $order = Order::query()->with(['items', 'city'])->findOrFail((int) $id);

        return view('store.checkout-thanks', compact('order'));
    }
}
