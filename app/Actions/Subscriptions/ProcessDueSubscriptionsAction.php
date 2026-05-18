<?php

namespace App\Actions\Subscriptions;

use App\Models\Subscription;
use Illuminate\Support\Carbon;

/**
 * Finds due subscriptions and marks them for order generation (Phase 2: delegate to CreateOrderAction with box lines).
 */
final class ProcessDueSubscriptionsAction
{
    public function execute(?Carbon $at = null): int
    {
        $at ??= now();

        return Subscription::query()
            ->where('status', 'active')
            ->where('next_order_at', '<=', $at)
            ->count();
    }
}
