<?php

namespace App\DTO;

final readonly class OrderLineDraftDto
{
    /**
     * @param  array<string, bool>|array<string, mixed>  $services
     * @param  array<string, mixed>|null  $productNameSnapshot
     */
    public function __construct(
        public ?int $productId,
        public ?int $produceBoxId,
        public array|null $productNameSnapshot,
        public string $unit,
        public string $quantity,
        public array $services,
        public string $packaging,
        public string $unitPrice,
        public string $servicesSurchargeTotal = '0',
    ) {}
}
