<?php

namespace App\Services\Cart;

final readonly class CartTotalsResult
{
    /**
     * @param  list<string>  $lineTotals
     */
    public function __construct(
        public array $lineTotals,
        public string $linesSubtotal,
        public string $orderPackagingFee,
        public string $grandTotal,
    ) {}
}
