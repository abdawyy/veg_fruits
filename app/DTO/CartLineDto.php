<?php

namespace App\DTO;

final readonly class CartLineDto
{
    /**
     * @param  array<string, bool>  $services  Selected preparation flags, e.g. ['washed' => true]
     */
    public function __construct(
        public string $unitPrice,
        public string $quantity,
        public array $services = [],
        public string $servicesSurchargeTotal = '0',
    ) {}
}
