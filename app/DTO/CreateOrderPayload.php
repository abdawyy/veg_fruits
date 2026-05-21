<?php

namespace App\DTO;

final readonly class CreateOrderPayload
{
    /**
     * @param  list<OrderLineDraftDto>  $lines
     */
    public function __construct(
        public ?int $userId,
        public int $cityId,
        public string $shippingAddressLine1,
        public ?string $shippingAddressLine2,
        public string $customerPhone,
        public ?string $customerName,
        public ?string $customerEmail,
        public string $packagingCode,
        public string $packagingFee,
        public array $lines,
        public ?string $notes = null,
        public string $paymentGatewayId = 'cod',
        public ?int $couponId = null,
        public string $discountAmount = '0',
    ) {}
}
