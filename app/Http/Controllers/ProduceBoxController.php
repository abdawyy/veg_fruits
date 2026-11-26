<?php

namespace App\Http\Controllers;

use App\Actions\Boxes\RecordProduceBoxViewAction;
use App\Enums\SubscriptionInterval;
use App\Models\City;
use App\Models\ProduceBox;
use App\Payments\PaymentGatewayResolver;
use App\Support\StoreCart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final class ProduceBoxController extends Controller
{
    public function index(): View
    {
        $boxes = ProduceBox::query()
            ->active()
            ->with(['items.product'])
            ->withCount('items')
            ->orderBy('id')
            ->get();

        return view('store.boxes.index', compact('boxes'));
    }

    public function show(ProduceBox $produceBox): View
    {
        abort_unless($produceBox->is_active, 404);

        app(RecordProduceBoxViewAction::class)->execute($produceBox);

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

    public function addToCart(Request $request, ProduceBox $produceBox): RedirectResponse|JsonResponse
    {
        abort_unless($produceBox->is_active, 404);

        if ($produceBox->items()->count() === 0) {
            throw ValidationException::withMessages([
                'box' => __('aldawy.box_has_no_items'),
            ]);
        }

        $data = $request->validate([
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);
        $quantity = $produceBox->show_card_input ? (int) ($data['quantity'] ?? 1) : 1;

        StoreCart::addBox(
            $produceBox->id,
            (string) $produceBox->price,
            (string) $quantity,
        );

        $message = __('aldawy.box_added_to_cart');
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'cart_line_count' => StoreCart::lineCount(),
            ]);
        }

        return redirect()
            ->route('store.cart')
            ->with('status', $message);
    }
}
