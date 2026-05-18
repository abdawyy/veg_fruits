<?php

namespace App\Notifications;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = OrderResource::getUrl('edit', ['record' => $this->order], panel: 'admin');

        return (new MailMessage)
            ->subject(__('aldawy.mail_admin_order_subject', ['ref' => $this->order->reference]))
            ->line(__('aldawy.mail_admin_order_body', [
                'ref' => $this->order->reference,
                'total' => number_format((float) $this->order->total, 2),
                'currency' => config('aldawy.currency', 'EGP'),
            ]))
            ->action(__('aldawy.mail_admin_order_cta'), $url);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => __('New order'),
            'body' => __('Order :ref — :total', [
                'ref' => $this->order->reference,
                'total' => $this->order->total,
            ]),
            'order_id' => $this->order->id,
        ];
    }
}
