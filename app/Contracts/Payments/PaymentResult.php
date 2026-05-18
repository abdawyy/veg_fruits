<?php

namespace App\Contracts\Payments;

final readonly class PaymentResult
{
    public function __construct(
        public bool $successful,
        public string $message = '',
    ) {}
}
