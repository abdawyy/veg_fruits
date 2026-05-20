<?php

namespace App\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use InvalidArgumentException;

final class PaymentGatewayResolver
{
    public function __construct(
        private readonly CashOnDeliveryGateway $cod,
        private readonly OnlinePendingPaymentGateway $online,
    ) {}

    public function resolve(string $identifier): PaymentGatewayInterface
    {
        return match ($identifier) {
            'cod' => $this->cod,
            'online' => $this->online,
            default => throw new InvalidArgumentException('Unknown payment gateway: '.$identifier),
        };
    }

    /** @return array<string, string> id => translated label */
    public function optionsForCheckout(): array
    {
        $options = [
            'cod' => __('aldawy.payment_cod'),
        ];

        if (config('aldawy.payments.online_enabled')) {
            $options['online'] = __('aldawy.payment_online');
        }

        return $options;
    }

    public function isAllowed(string $identifier): bool
    {
        return array_key_exists($identifier, $this->optionsForCheckout());
    }
}
