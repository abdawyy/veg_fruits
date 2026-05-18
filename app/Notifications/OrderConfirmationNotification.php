<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationNotification extends Notification
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
        $this->order->loadMissing(['items', 'city']);

        $loc = app()->getLocale();
        $lines = [];
        foreach ($this->order->items as $item) {
            $name = '—';
            if (is_array($item->product_name_snapshot)) {
                $name = (string) ($item->product_name_snapshot[$loc] ?? $item->product_name_snapshot['en'] ?? $item->product_name_snapshot['ar'] ?? '—');
            }
            $lines[] = $name.' — '.(string) $item->quantity.' '.$item->unit.' — '.number_format((float) $item->line_total, 2);
        }

        $mail = (new MailMessage)
            ->subject(__('aldawy.mail_order_subject', ['ref' => $this->order->reference]))
            ->greeting(__('aldawy.mail_order_greeting'))
            ->line(__('aldawy.mail_order_intro', ['ref' => $this->order->reference]));

        $cityName = $this->order->city?->getTranslation('name', $loc)
            ?? $this->order->city?->getTranslation('name', 'en')
            ?? '—';
        $addr = trim((string) $this->order->shipping_address_line1);
        if ($this->order->shipping_address_line2) {
            $addr .= ($addr !== '' ? ', ' : '').trim((string) $this->order->shipping_address_line2);
        }
        if ($addr !== '' || $this->order->city) {
            $mail->line(__('aldawy.mail_order_delivery', [
                'address' => $addr !== '' ? $addr : '—',
                'city' => $cityName,
                'ship' => number_format((float) $this->order->shipping_fee, 2),
                'currency' => config('aldawy.currency', 'EGP'),
            ]));
        }

        foreach ($lines as $line) {
            $mail->line('• '.$line);
        }

        return $mail
            ->line(__('aldawy.mail_order_total', [
                'total' => number_format((float) $this->order->total, 2),
                'currency' => config('aldawy.currency', 'EGP'),
            ]))
            ->line(__('aldawy.mail_order_cod'))
            ->action(__('aldawy.mail_order_invoice_cta'), $this->downloadUrl)
            ->line(__('aldawy.mail_order_footer'));
    }
}
