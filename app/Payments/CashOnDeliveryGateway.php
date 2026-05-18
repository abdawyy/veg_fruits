<?php

namespace App\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Contracts\Payments\PaymentResult;
use App\Models\Order;

final class CashOnDeliveryGateway implements PaymentGatewayInterface
{
    public function getIdentifier(): string
    {
        return 'cod';
    }

    public function handleCheckout(Order $order): PaymentResult
    {
        return new PaymentResult(true, 'Cash on delivery');
    }
}
