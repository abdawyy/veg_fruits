<?php

namespace App\Actions\Subscriptions;

use App\Actions\Orders\BuildOrderLinesFromProduceBoxAction;
use App\Actions\Orders\CreateOrderAction;
use App\DTO\CreateOrderPayload;
use App\Enums\SubscriptionInterval;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

final class ProcessDueSubscriptionsAction
{
    public function __construct(
        private readonly BuildOrderLinesFromProduceBoxAction $buildLines,
        private readonly CreateOrderAction $createOrder,
    ) {}

    public function execute(?Carbon $at = null): int
    {
        $at ??= now();
        $processed = 0;

        $due = Subscription::query()
            ->with(['user', 'produceBox'])
            ->where('status', 'active')
            ->where('next_order_at', '<=', $at)
            ->orderBy('id')
            ->get();

        foreach ($due as $subscription) {
            if (! $this->processOne($subscription, $at)) {
                continue;
            }
            $processed++;
        }

        return $processed;
    }

    private function processOne(Subscription $subscription, Carbon $at): bool
    {
        $user = $subscription->user;
        $box = $subscription->produceBox;

        if (! $user instanceof User || ! $box || ! $box->is_active) {
            Log::warning('[subscriptions] Skipping subscription — missing user or inactive box.', [
                'subscription_id' => $subscription->id,
            ]);

            return false;
        }

        if ($user->default_city_id === null || $user->default_address_line1 === null || $user->default_address_line1 === '') {
            Log::warning('[subscriptions] Skipping subscription — user has no default delivery address.', [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
            ]);

            return false;
        }

        $lines = $this->buildLines->execute($box, asSingleBoxLine: false);
        if ($lines === []) {
            Log::warning('[subscriptions] Skipping subscription — box has no valid items.', [
                'subscription_id' => $subscription->id,
            ]);

            return false;
        }

        $interval = $subscription->interval instanceof SubscriptionInterval
            ? $subscription->interval
            : SubscriptionInterval::tryFrom((string) $subscription->interval) ?? SubscriptionInterval::Monthly;

        try {
            $this->createOrder->execute(new CreateOrderPayload(
                userId: $user->id,
                cityId: (int) $user->default_city_id,
                shippingAddressLine1: (string) $user->default_address_line1,
                shippingAddressLine2: $user->default_address_line2,
                customerPhone: (string) ($user->phone_number ?? ''),
                customerName: $user->name,
                customerEmail: $user->email,
                packagingCode: '',
                packagingFee: '0',
                lines: $lines,
                notes: __('aldawy.subscription_renewal_note', [
                    'box' => $box->getTranslation('name', 'en'),
                    'interval' => $interval->label(),
                ]),
                paymentGatewayId: 'cod',
            ));

            $subscription->forceFill([
                'last_generated_at' => $at,
                'next_order_at' => $interval->nextAfter($at),
            ])->save();

            return true;
        } catch (\Throwable $e) {
            Log::error('[subscriptions] Failed to generate order.', [
                'subscription_id' => $subscription->id,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
