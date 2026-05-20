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

        $totals = $this->cartTotals->execute($cartLines, $payload->packagingFee);

        $paymentGateway = $this->paymentGateways->resolve($payload->paymentGatewayId);

        $city = City::query()->whereKey($payload->cityId)->where('is_active', true)->firstOrFail();
        $shippingFee = DecimalMath::normalizeNumericString((string) $city->shipping_fee);
        $grandTotal = DecimalMath::add($totals->grandTotal, $shippingFee);

        $order = DB::transaction(function () use ($payload, $totals, $city, $shippingFee, $grandTotal, $paymentGateway) {
            $order = Order::query()->create([
                'reference' => 'AL-'.strtoupper(Str::random(10)),
                'user_id' => $payload->userId,
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
                'shipping_fee' => $shippingFee,
                'total' => $grandTotal,
                'notes' => $payload->notes,
            ]);

            foreach ($payload->lines as $index => $line) {
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

            return $order->fresh();
        });

        OrderCreated::dispatch($order);

        return $order;
    }
}
