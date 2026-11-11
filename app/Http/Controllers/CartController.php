<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Product;
use App\Payments\PaymentGatewayResolver;
use App\Services\Coupons\ApplyCouponService;
use App\Services\Money\DecimalMath;
use App\Support\MinimumOrder;
use App\Support\Money\MoneyCents;
use App\Support\StoreCart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class CartController extends Controller
{
    public function index(Request $request): View
    {
        $rows = StoreCart::resolved();
        $subtotal = StoreCart::subtotal();
        $cities = City::query()->forStorefront()->get();
        $priceChangedLines = StoreCart::priceChangedLines();
        $user = $request->user();
        $paymentOptions = app(PaymentGatewayResolver::class)->optionsForCheckout();

        if ($rows->isNotEmpty()) {
            $request->session()->put('checkout_nonce', Str::random(40));
        }

        $couponCode = StoreCart::couponCode();
        $discountAmount = '0';
        $appliedCoupon = null;
        if ($couponCode !== null && bccomp($subtotal, '0', 4) > 0) {
            try {
                $applied = app(ApplyCouponService::class)->execute($couponCode, $subtotal);
                $discountAmount = $applied['discount'];
                $appliedCoupon = $applied['coupon'];
            } catch (ValidationException $e) {
                StoreCart::setCouponCode(null);
                $couponCode = null;
            }
        }

        $shippingCentsByCity = [];
        foreach ($cities as $city) {
            $shippingCentsByCity[$city->id] = MoneyCents::fromDecimal((string) $city->shipping_fee);
        }

        return view('store.cart', [
            'rows' => $rows,
            'subtotal' => $subtotal,
            'subtotalCents' => MoneyCents::fromDecimal($subtotal),
            'discountCents' => MoneyCents::fromDecimal($discountAmount),
            'shippingCentsByCity' => $shippingCentsByCity,
            'discountAmount' => $discountAmount,
            'couponCode' => $couponCode,
            'appliedCoupon' => $appliedCoupon,
            'cities' => $cities,
            'priceChangedLines' => $priceChangedLines,
            'paymentOptions' => $paymentOptions,
            'defaultCityId' => old('city_id', $user?->default_city_id),
            'defaultAddressLine1' => old('shipping_address_line1', $user?->default_address_line1),
            'defaultAddressLine2' => old('shipping_address_line2', $user?->default_address_line2),
            'defaultPhone' => old('customer_phone', $user?->phone_number),
            'defaultName' => old('customer_name', $user?->name),
            'defaultEmail' => old('customer_email', $user?->email),
            'minOrderShortfall' => MinimumOrder::shortfall($subtotal),
            'minOrderAmount' => MinimumOrder::amount(),
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
            'unit' => ['sometimes', Rule::in(StoreCart::units())],
            'quantity' => ['sometimes', 'numeric', 'min:0.01', 'max:500000'],
            'kg' => ['sometimes', 'numeric', 'min:0.25', 'max:500'],
            'preparation_service_ids' => ['sometimes', 'array'],
            'preparation_service_ids.*' => ['integer', 'exists:preparation_services,id'],
            'packaging_type_id' => ['nullable', 'integer', 'exists:packaging_types,id'],
        ]);

        $product = Product::query()
            ->whereKey((int) $data['product_id'])
            ->where('is_active', true)
            ->firstOrFail();

        [$unit, $quantity] = $this->resolveUnitQuantity($data, $product);
        $this->assertQuantityCap($unit, $quantity);
        $this->assertMinimumQuantity($product, $unit, $quantity);

        $unitPrice = StoreCart::unitPriceForProduct($product, $unit);
        if ($unitPrice === null) {
            throw ValidationException::withMessages([
                'unit' => [__('aldawy.cart_unit_unavailable')],
            ]);
        }

        if ($product->isOutOfStock() || ! $product->hasStockFor($quantity, $unit)) {
            throw ValidationException::withMessages([
                'product_id' => [__('aldawy.stock_insufficient')],
            ]);
        }

        StoreCart::add(
            (int) $data['product_id'],
            $unit,
            $quantity,
            $data['preparation_service_ids'] ?? [],
            isset($data['packaging_type_id']) ? (int) $data['packaging_type_id'] : null,
            $unitPrice,
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
            'unit' => ['required', Rule::in(StoreCart::units())],
            'quantity' => ['required', 'numeric', 'min:0.01', 'max:500000'],
        ]);

        $this->assertLineQuantityRules((int) $data['line'], $data['unit'], (string) $data['quantity']);

        StoreCart::update((int) $data['line'], $data['unit'], (string) $data['quantity']);

        return back()->with('status', __('aldawy.cart_updated'));
    }

    public function updateOptions(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'line' => ['required', 'integer', 'min:0'],
            'unit' => ['required', Rule::in(StoreCart::units())],
            'quantity' => ['required', 'numeric', 'min:0.01', 'max:500000'],
            'preparation_service_ids' => ['sometimes', 'array'],
            'preparation_service_ids.*' => ['integer', 'exists:preparation_services,id'],
            'packaging_type_id' => ['nullable', 'integer', 'exists:packaging_types,id'],
        ]);

        $this->assertLineQuantityRules((int) $data['line'], $data['unit'], (string) $data['quantity']);

        StoreCart::updateLine(
            (int) $data['line'],
            $data['unit'],
            (string) $data['quantity'],
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

    public function applyCoupon(Request $request, ApplyCouponService $coupons): RedirectResponse
    {
        $data = $request->validate([
            'coupon_code' => ['required', 'string', 'max:32'],
        ]);

        $subtotal = StoreCart::subtotal();
        if (bccomp($subtotal, '0', 4) <= 0) {
            throw ValidationException::withMessages([
                'coupon_code' => [__('aldawy.coupon_cart_empty')],
            ]);
        }

        $coupons->execute($data['coupon_code'], $subtotal);
        StoreCart::setCouponCode($data['coupon_code']);

        return back()->with('status', __('aldawy.coupon_applied'));
    }

    public function removeCoupon(): RedirectResponse
    {
        StoreCart::setCouponCode(null);

        return back()->with('status', __('aldawy.coupon_removed'));
    }

    private function assertLineQuantityRules(int $lineIndex, string $unit, string $quantity): void
    {
        $lines = StoreCart::lines();
        if (! isset($lines[$lineIndex]) || ($lines[$lineIndex]['type'] ?? StoreCart::KIND_PRODUCT) !== StoreCart::KIND_PRODUCT) {
            return;
        }

        $product = Product::query()->find((int) $lines[$lineIndex]['product_id']);
        if (! $product) {
            throw ValidationException::withMessages([
                'quantity' => [__('aldawy.stock_insufficient')],
            ]);
        }

        if (! $product->allowsUnit($unit)) {
            throw ValidationException::withMessages([
                'unit' => [__('aldawy.cart_unit_unavailable')],
            ]);
        }

        $this->assertQuantityCap($unit, $quantity);
        $this->assertMinimumQuantity($product, $unit, $quantity);

        if ($product->isOutOfStock() || ! $product->hasStockFor($quantity, $unit)) {
            throw ValidationException::withMessages([
                'quantity' => [__('aldawy.stock_insufficient')],
            ]);
        }
    }

    private function assertQuantityCap(string $unit, string $quantity): void
    {
        $maximum = StoreCart::maxQuantity($unit);
        if (bccomp(DecimalMath::normalizeNumericString($quantity), $maximum, 4) > 0) {
            throw ValidationException::withMessages([
                'quantity' => [__('aldawy.cart_max_quantity', [
                    'maximum' => StoreCart::formatQuantity($maximum, $unit),
                    'unit' => StoreCart::unitLabel($unit),
                ])],
            ]);
        }
    }

    private function assertMinimumQuantity(Product $product, string $unit, string $quantity): void
    {
        $minimum = $product->minimumOrderQuantity($unit);
        if (bccomp($quantity, $minimum, 4) < 0) {
            throw ValidationException::withMessages([
                'quantity' => [__('aldawy.cart_min_quantity', [
                    'minimum' => StoreCart::formatQuantity($minimum, $unit),
                    'unit' => StoreCart::unitLabel($unit),
                ])],
            ]);
        }
    }

    /** @param  array<string, mixed>  $data */
    private function resolveUnitQuantity(array $data, Product $product): array
    {
        $unit = $data['unit'] ?? $product->sellingUnit();
        if (isset($data['quantity'])) {
            $quantity = (string) $data['quantity'];
        } elseif (isset($data['kg'])) {
            $unit = StoreCart::UNIT_KG;
            $quantity = (string) $data['kg'];
        } else {
            $quantity = $product->minimumOrderQuantity($unit);
        }

        if (! $product->allowsUnit($unit)) {
            throw ValidationException::withMessages([
                'unit' => [__('aldawy.cart_unit_unavailable')],
            ]);
        }

        return [$unit, $quantity];
    }
}
