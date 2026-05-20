<?php

namespace App\Notifications;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class OrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public OrderStatus $previousStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->order->loadMissing('city');

        return (new MailMessage)
            ->subject(__('aldawy.mail_status_subject', ['ref' => $this->order->reference]))
            ->greeting(__('aldawy.mail_order_greeting'))
            ->line(__('aldawy.mail_status_intro', [
                'ref' => $this->order->reference,
                'status' => $this->order->status->label(),
            ]))
            ->line(__('aldawy.mail_status_footer'));
    }
}
