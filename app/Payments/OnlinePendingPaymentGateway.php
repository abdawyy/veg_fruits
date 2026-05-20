<?php

namespace App\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Contracts\Payments\PaymentResult;
use App\Enums\OrderStatus;
use App\Models\Order;

/**
 * Placeholder for card/wallet integrations — order stays pending until staff confirms payment.
 */
final class OnlinePendingPaymentGateway implements PaymentGatewayInterface
{
    public function getIdentifier(): string
    {
        return 'online';
    }

    public function handleCheckout(Order $order): PaymentResult
    {
        $order->forceFill([
            'status' => OrderStatus::Pending,
            'notes' => trim(((string) $order->notes)."\n".__('aldawy.payment_online_pending_note')),
        ])->save();

        return new PaymentResult(true, __('aldawy.payment_online_pending_admin'));
    }
}
