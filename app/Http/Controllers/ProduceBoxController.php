<?php

namespace App\Http\Controllers;

use App\Enums\SubscriptionInterval;
use App\Models\City;
use App\Models\ProduceBox;
use App\Payments\PaymentGatewayResolver;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ProduceBoxController extends Controller
{
    public function index(): View
    {
        $boxes = ProduceBox::query()
            ->active()
            ->withCount('items')
            ->orderBy('id')
            ->get();

        return view('store.boxes.index', compact('boxes'));
    }

    public function show(ProduceBox $produceBox): View
    {
        abort_unless($produceBox->is_active, 404);

        $produceBox->load(['items.product.category']);

        $cities = City::query()->forStorefront()->get();
        $paymentOptions = app(PaymentGatewayResolver::class)->optionsForCheckout();
        $user = auth()->user();

        return view('store.boxes.show', [
            'box' => $produceBox,
            'cities' => $cities,
            'paymentOptions' => $paymentOptions,
            'intervals' => SubscriptionInterval::cases(),
            'defaultCityId' => old('city_id', $user?->default_city_id),
            'defaultAddressLine1' => old('shipping_address_line1', $user?->default_address_line1),
            'defaultAddressLine2' => old('shipping_address_line2', $user?->default_address_line2),
            'defaultPhone' => old('customer_phone', $user?->phone_number),
            'defaultName' => old('customer_name', $user?->name),
            'defaultEmail' => old('customer_email', $user?->email),
        ]);
    }

    public function addToCart(Request $request, ProduceBox $produceBox): RedirectResponse
    {
        abort_unless($produceBox->is_active, 404);

        if ($produceBox->items()->count() === 0) {
            return back()->withErrors(['box' => __('aldawy.box_has_no_items')]);
        }

        \App\Support\StoreCart::addBox(
            $produceBox->id,
            (string) $produceBox->price,
        );

        return redirect()
            ->route('store.cart')
            ->with('status', __('aldawy.box_added_to_cart'));
    }
}
