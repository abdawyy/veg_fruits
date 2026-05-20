<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Product;
use App\Support\StoreCart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

final class CartController extends Controller
{
    public function index(Request $request): View
    {
        $rows = StoreCart::resolved();
        $subtotal = StoreCart::subtotal();
        $cities = City::query()->forStorefront()->get();
        $priceChangedLines = StoreCart::priceChangedLines();
        $user = $request->user();

        if ($rows->isNotEmpty()) {
            $request->session()->put('checkout_nonce', Str::random(40));
        }

        return view('store.cart', [
            'rows' => $rows,
            'subtotal' => $subtotal,
            'cities' => $cities,
            'priceChangedLines' => $priceChangedLines,
            'defaultCityId' => old('city_id', $user?->default_city_id),
            'defaultAddressLine1' => old('shipping_address_line1', $user?->default_address_line1),
            'defaultAddressLine2' => old('shipping_address_line2', $user?->default_address_line2),
            'defaultPhone' => old('customer_phone', $user?->phone_number),
            'defaultName' => old('customer_name', $user?->name),
            'defaultEmail' => old('customer_email', $user?->email),
        ]);
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->where(fn ($q) => $q->where('is_active', true)),
            ],
            'kg' => ['required', 'numeric', 'min:0.25', 'max:500'],
            'preparation_service_ids' => ['sometimes', 'array'],
            'preparation_service_ids.*' => ['integer', 'exists:preparation_services,id'],
            'packaging_type_id' => ['nullable', 'integer', 'exists:packaging_types,id'],
        ]);

        $product = Product::query()
            ->whereKey((int) $data['product_id'])
            ->where('is_active', true)
            ->firstOrFail();

        StoreCart::add(
            (int) $data['product_id'],
            (string) $data['kg'],
            $data['preparation_service_ids'] ?? [],
            isset($data['packaging_type_id']) ? (int) $data['packaging_type_id'] : null,
            (string) $product->price_per_kg,
        );

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => __('aldawy.cart_added'),
                'cart_line_count' => StoreCart::lineCount(),
            ]);
        }

        return back()->with('status', __('aldawy.cart_added'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'line' => ['required', 'integer', 'min:0'],
            'kg' => ['required', 'numeric', 'min:0.25', 'max:500'],
        ]);

        StoreCart::update((int) $data['line'], (string) $data['kg']);

        return back()->with('status', __('aldawy.cart_updated'));
    }

    public function updateOptions(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'line' => ['required', 'integer', 'min:0'],
            'kg' => ['required', 'numeric', 'min:0.25', 'max:500'],
            'preparation_service_ids' => ['sometimes', 'array'],
            'preparation_service_ids.*' => ['integer', 'exists:preparation_services,id'],
            'packaging_type_id' => ['nullable', 'integer', 'exists:packaging_types,id'],
        ]);

        StoreCart::updateLine(
            (int) $data['line'],
            (string) $data['kg'],
            $data['preparation_service_ids'] ?? [],
            isset($data['packaging_type_id']) ? (int) $data['packaging_type_id'] : null,
        );

        return back()->with('status', __('aldawy.cart_options_updated'));
    }

    public function remove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'line' => ['required', 'integer', 'min:0'],
        ]);

        StoreCart::remove((int) $data['line']);

        return back()->with('status', __('aldawy.cart_removed'));
    }

    public function clear(): RedirectResponse
    {
        StoreCart::clear();

        return redirect()->route('store.cart')->with('status', __('aldawy.cart_cleared'));
    }
}
