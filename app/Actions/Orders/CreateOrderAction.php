<?php

namespace App\Actions\Orders;

use App\Payments\PaymentGatewayResolver;
use App\DTO\CartLineDto;
use App\DTO\CreateOrderPayload;
use App\DTO\OrderLineDraftDto;
use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart\CalculateCartTotalService;
use App\Services\Money\DecimalMath;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreateOrderAction
{
    public function __construct(
        private readonly CalculateCartTotalService $cartTotals,
        private readonly PaymentGatewayResolver $paymentGateways,
    ) {}

    public function execute(CreateOrderPayload $payload): Order
    {
        $cartLines = [];
        foreach ($payload->lines as $line) {
            if (! $line instanceof OrderLineDraftDto) {
                throw new \InvalidArgumentException('Invalid order line payload.');
            }
            $cartLines[] = new CartLineDto(
                unitPrice: $line->unitPrice,
                quantity: $line->quantity,
                services: $line->services,
                servicesSurchargeTotal: $line->servicesSurchargeTotal,
            );
        }

        // packagingFee on payload is order-level only; per-line prep/packaging surcharges are in each line's servicesSurchargeTotal.
        $totals = $this->cartTotals->execute($cartLines, $payload->packagingFee);

        $paymentGateway = $this->paymentGateways->resolve($payload->paymentGatewayId);

        $city = City::query()->whereKey($payload->cityId)->where('is_active', true)->firstOrFail();
        $shippingFee = DecimalMath::normalizeNumericString((string) $city->shipping_fee);
        $discount = DecimalMath::normalizeNumericString($payload->discountAmount);
        $afterDiscount = DecimalMath::sub($totals->grandTotal, $discount);
        if (bccomp($afterDiscount, '0', 4) < 0) {
            $afterDiscount = '0';
        }
        $grandTotal = DecimalMath::add($afterDiscount, $shippingFee);

        $order = DB::transaction(function () use ($payload, $totals, $city, $shippingFee, $grandTotal, $paymentGateway, $discount) {
            $order = Order::query()->create([
                'reference' => 'AL-'.strtoupper(Str::random(10)),
                'user_id' => $payload->userId,
                'coupon_id' => $payload->couponId,
                'city_id' => $city->id,
                'shipping_address_line1' => $payload->shippingAddressLine1,
                'shipping_address_line2' => $payload->shippingAddressLine2,
                'customer_phone' => $payload->customerPhone,
                'customer_name' => $payload->customerName,
                'customer_email' => $payload->customerEmail,
                'status' => OrderStatus::Pending,
                'payment_gateway' => $paymentGateway->getIdentifier(),
                'packaging_code' => $payload->packagingCode,
                'subtotal' => $totals->linesSubtotal,
                'packaging_fee' => $totals->orderPackagingFee,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'total' => $grandTotal,
                'notes' => $payload->notes,
            ]);

            foreach ($payload->lines as $index => $line) {
                if ($line->productId !== null) {
                    $product = \App\Models\Product::query()->lockForUpdate()->find($line->productId);
                    if ($product && $product->track_stock && $product->stock_quantity !== null) {
                        $remaining = DecimalMath::sub((string) $product->stock_quantity, $line->quantity);
                        if (bccomp($remaining, '0', 4) < 0) {
                            throw new \RuntimeException(__('aldawy.stock_insufficient'));
                        }
                        $product->forceFill(['stock_quantity' => $remaining])->save();
                    }
                }

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $line->productId,
                    'produce_box_id' => $line->produceBoxId,
                    'product_name_snapshot' => $line->productNameSnapshot,
                    'unit' => $line->unit,
                    'quantity' => $line->quantity,
                    'services' => $line->services,
                    'packaging' => $line->packaging,
                    'unit_price' => $line->unitPrice,
                    'line_total' => $totals->lineTotals[$index],
                ]);
            }

            $paymentGateway->handleCheckout($order->fresh());

            if ($payload->couponId !== null) {
                \App\Models\Coupon::query()->whereKey($payload->couponId)->increment('used_count');
            }

            return $order->fresh();
        });

        OrderCreated::dispatch($order);

        return $order;
    }
}
