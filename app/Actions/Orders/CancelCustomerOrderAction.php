<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Validation\ValidationException;

final class CancelCustomerOrderAction
{
    public function execute(Order $order, User $user): Order
    {
        if ((int) $order->user_id !== (int) $user->id) {
            throw ValidationException::withMessages([
                'order' => [__('aldawy.order_cancel_forbidden')],
            ]);
        }

        if (! $order->canCustomerCancel()) {
            throw ValidationException::withMessages([
                'order' => [__('aldawy.order_cancel_not_allowed')],
            ]);
        }

        $order->forceFill(['status' => OrderStatus::Cancelled])->save();

        return $order->fresh();
    }
}
