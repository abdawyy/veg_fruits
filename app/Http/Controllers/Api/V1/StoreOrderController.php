<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Orders\CreateOrderAction;
use App\DTO\CreateOrderPayload;
use App\DTO\OrderLineDraftDto;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\City;
use App\Models\Product;
use App\Payments\PaymentGatewayResolver;
use App\Services\Coupons\ApplyCouponService;
use App\Services\Money\DecimalMath;
use App\Services\Pricing\SurchargeOnBase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class StoreOrderController extends Controller
{
    public function store(
        Request $request,
        CreateOrderAction $createOrder,
        PaymentGatewayResolver $paymentGateways,
    ): JsonResponse {
        $data = $request->validate([
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'shipping_address_line1' => ['required', 'string', 'max:255'],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:32'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'string', 'in:'.implode(',', array_keys($paymentGateways->optionsForCheckout()))],
            'coupon_code' => ['nullable', 'string', 'max:32'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'lines.*.unit' => ['required', Rule::in(['kg', 'piece'])],
            'lines.*.quantity' => ['required', 'numeric', 'min:0.01', 'max:500'],
            'lines.*.preparation_service_ids' => ['sometimes', 'array'],
            'lines.*.preparation_service_ids.*' => ['integer', 'exists:preparation_services,id'],
            'lines.*.packaging_type_id' => ['nullable', 'integer', 'exists:packaging_types,id'],
        ]);

        City::query()->whereKey((int) $data['city_id'])->where('is_active', true)->firstOrFail();

        $draftLines = [];
        $linesSubtotal = '0';

        foreach ($data['lines'] as $row) {
            $product = Product::query()
                ->active()
                ->with([
                    'preparationServices' => fn ($q) => $q->where('preparation_services.is_active', true),
                    'packagingTypes' => fn ($q) => $q->where('packaging_types.is_active', true),
                ])
                ->findOrFail((int) $row['product_id']);

            if ($product->isOutOfStock() || ! $product->hasStockFor((string) $row['quantity'])) {
                return response()->json(['message' => __('aldawy.stock_insufficient'), 'product_id' => $product->id], 422);
            }

            $unit = $row['unit'];
            $qty = DecimalMath::normalizeNumericString((string) $row['quantity']);
            $unitPrice = $unit === 'piece'
                ? ($product->price_per_piece !== null ? DecimalMath::normalizeNumericString((string) $product->price_per_piece) : null)
                : DecimalMath::normalizeNumericString((string) $product->price_per_kg);

            if ($unitPrice === null) {
                return response()->json(['message' => __('aldawy.cart_unit_unavailable')], 422);
            }

            $lineBase = DecimalMath::mul($unitPrice, $qty);
            $prepIds = array_map('intval', $row['preparation_service_ids'] ?? []);
            $selectedPrep = $product->preparationServices->whereIn('id', $prepIds)->values();
            $servicesSurcharge = SurchargeOnBase::sumForRules($lineBase, $selectedPrep);
            $packId = isset($row['packaging_type_id']) ? (int) $row['packaging_type_id'] : null;
            $packaging = $packId ? $product->packagingTypes->firstWhere('id', $packId) : null;
            $amountAfterServices = DecimalMath::add($lineBase, $servicesSurcharge);
            $packagingSurcharge = $packaging
                ? SurchargeOnBase::amount($amountAfterServices, $packaging)
                : DecimalMath::normalizeNumericString('0');
            $extras = DecimalMath::add($servicesSurcharge, $packagingSurcharge);
            $lineTotal = DecimalMath::add($amountAfterServices, $packagingSurcharge);
            $linesSubtotal = DecimalMath::add($linesSubtotal, $lineTotal);

            $servicesMap = [];
            foreach ($selectedPrep as $ps) {
                $servicesMap[$ps->code] = true;
            }

            $draftLines[] = new OrderLineDraftDto(
                productId: $product->id,
                produceBoxId: null,
                productNameSnapshot: $product->getTranslations('name'),
                unit: $unit,
                quantity: $qty,
                services: $servicesMap,
                packaging: $packaging ? (string) $packaging->code : '',
                unitPrice: $unitPrice,
                servicesSurchargeTotal: $extras,
            );
        }

        $couponId = null;
        $discountAmount = '0';
        if (! empty($data['coupon_code'])) {
            $applied = app(ApplyCouponService::class)->execute($data['coupon_code'], $linesSubtotal);
            $couponId = $applied['coupon']->id;
            $discountAmount = $applied['discount'];
        }

        $order = $createOrder->execute(new CreateOrderPayload(
            userId: $request->user()?->id,
            cityId: (int) $data['city_id'],
            shippingAddressLine1: $data['shipping_address_line1'],
            shippingAddressLine2: $data['shipping_address_line2'] ?? null,
            customerPhone: $data['customer_phone'],
            customerName: $data['customer_name'] ?? $request->user()?->name,
            customerEmail: $data['customer_email'] ?? $request->user()?->email,
            packagingCode: '',
            packagingFee: '0',
            lines: $draftLines,
            notes: $data['notes'] ?? null,
            paymentGatewayId: $data['payment_method'],
            couponId: $couponId,
            discountAmount: $discountAmount,
        ));

        return (new OrderResource($order->load('items')))->response()->setStatusCode(201);
    }
}
