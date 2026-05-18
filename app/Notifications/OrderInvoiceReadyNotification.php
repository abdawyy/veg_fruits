<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderInvoiceReadyNotification extends Notification
{
    public function __construct(
        public Order $order,
        public string $downloadUrl,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('AL-DAWY — Your invoice'))
            ->line(__('Thank you for your order :ref.', ['ref' => $this->order->reference]))
            ->action(__('Download invoice'), $this->downloadUrl);
    }
}
