<?php

namespace App\Actions\Subscriptions;

use App\Actions\Orders\BuildOrderLinesFromProduceBoxAction;
use App\Actions\Orders\CreateOrderAction;
use App\DTO\CreateOrderPayload;
use App\Enums\SubscriptionInterval;
use App\Models\Order;
use App\Models\ProduceBox;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SubscribeToProduceBoxAction
{
    public function __construct(
        private readonly BuildOrderLinesFromProduceBoxAction $buildLines,
        private readonly CreateOrderAction $createOrder,
    ) {}

    /**
     * @param  array<string, mixed>  $delivery
     * @return array{subscription: Subscription, order: Order}
     */
    public function execute(
        User $user,
        ProduceBox $box,
        SubscriptionInterval $interval,
        array $delivery,
        string $paymentGatewayId = 'cod',
    ): array {
        if (! $box->is_active) {
            throw ValidationException::withMessages([
                'produce_box_id' => [__('aldawy.box_unavailable')],
            ]);
        }

        $lines = $this->buildLines->execute($box, asSingleBoxLine: false);
        if ($lines === []) {
            throw ValidationException::withMessages([
                'produce_box_id' => [__('aldawy.box_has_no_items')],
            ]);
        }

        $startsAt = Carbon::now();

        return DB::transaction(function () use ($user, $box, $interval, $delivery, $paymentGatewayId, $lines, $startsAt) {
            $subscription = Subscription::query()->create([
                'user_id' => $user->id,
                'produce_box_id' => $box->id,
                'interval' => $interval,
                'status' => 'active',
                'starts_at' => $startsAt,
                'next_order_at' => $interval->nextAfter($startsAt),
                'last_generated_at' => $startsAt,
            ]);

            $order = $this->createOrder->execute(new CreateOrderPayload(
                userId: $user->id,
                cityId: (int) $delivery['city_id'],
                shippingAddressLine1: $delivery['shipping_address_line1'],
                shippingAddressLine2: $delivery['shipping_address_line2'] ?? null,
                customerPhone: $delivery['customer_phone'],
                customerName: $delivery['customer_name'] ?? $user->name,
                customerEmail: $delivery['customer_email'] ?? $user->email,
                packagingCode: '',
                packagingFee: '0',
                lines: $lines,
                notes: __('aldawy.subscription_first_order_note', ['box' => $box->getTranslation('name', app()->getLocale())]),
                paymentGatewayId: $paymentGatewayId,
            ));

            $user->forceFill([
                'default_city_id' => (int) $delivery['city_id'],
                'default_address_line1' => $delivery['shipping_address_line1'],
                'default_address_line2' => $delivery['shipping_address_line2'] ?? null,
            ])->save();

            return ['subscription' => $subscription, 'order' => $order];
        });
    }
}
