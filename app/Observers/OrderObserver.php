<?php

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Support\Facades\Notification;

final class OrderObserver
{
    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        $previous = $order->getOriginal('status');
        $previousStatus = $previous instanceof OrderStatus
            ? $previous
            : OrderStatus::tryFrom((string) $previous);

        if ($previousStatus === null || $previousStatus === $order->status) {
            return;
        }

        $order->loadMissing('user');

        $notification = new OrderStatusChangedNotification($order, $previousStatus);

        if ($order->customer_email) {
            Notification::route('mail', $order->customer_email)->notify($notification);
        }

        if ($order->user?->email && strtolower($order->user->email) !== strtolower((string) $order->customer_email)) {
            $order->user->notify($notification);
        }
    }
}
