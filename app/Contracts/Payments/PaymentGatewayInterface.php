<?php

namespace App\Contracts\Payments;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function getIdentifier(): string;

    public function handleCheckout(Order $order): PaymentResult;
}
