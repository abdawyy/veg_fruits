<?php

namespace App\Listeners;

use App\Actions\Invoices\GenerateInvoicePdfAction;
use App\Contracts\Sms\SmsSenderInterface;
use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\AdminNewOrderNotification;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

final class OnOrderCreatedGenerateInvoiceAndNotify implements ShouldQueue
{
    public function __construct(
        private readonly GenerateInvoicePdfAction $invoicePdf,
        private readonly SmsSenderInterface $sms,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        $this->invoicePdf->execute($order);

        $signed = URL::temporarySignedRoute(
            'invoices.download',
            now()->addDays(30),
            ['order' => $order->id],
        );

        $this->sms->send($order->customer_phone, __('Your AL-DAWY invoice is ready. Download: :url', ['url' => $signed]));

        $order->loadMissing('user');
        $confirmation = new OrderConfirmationNotification($order, $signed);

        $emailed = [];
        if ($order->customer_email) {
            Notification::route('mail', $order->customer_email)->notify($confirmation);
            $emailed[strtolower($order->customer_email)] = true;
        }

        if ($order->user?->email && ! isset($emailed[strtolower($order->user->email)])) {
            $order->user->notify($confirmation);
        }

        User::query()
            ->where('is_admin', true)
            ->whereNotNull('email')
            ->each(fn (User $admin) => $admin->notify(new AdminNewOrderNotification($order)));
    }
}
