<?php

namespace App\Services\Cart;

use App\DTO\CartLineDto;
use App\Services\Money\DecimalMath;

final class CalculateCartTotalService
{
    public function execute(array $lines, string $orderPackagingFee = '0'): CartTotalsResult
    {
        $scale = DecimalMath::scale();
        $zero = DecimalMath::normalizeNumericString('0');
        $packaging = DecimalMath::normalizeNumericString($orderPackagingFee);

        $lineTotals = [];
        $linesSubtotal = $zero;

        foreach ($lines as $line) {
            if (! $line instanceof CartLineDto) {
                throw new \InvalidArgumentException('Each cart line must be a '.CartLineDto::class);
            }

            $unit = DecimalMath::normalizeNumericString($line->unitPrice);
            $qty = DecimalMath::normalizeNumericString($line->quantity);
            $base = DecimalMath::mul($unit, $qty);
            $svc = DecimalMath::normalizeNumericString($line->servicesSurchargeTotal);
            $lineTotal = DecimalMath::add($base, $svc);
            $lineTotals[] = $lineTotal;
            $linesSubtotal = DecimalMath::add($linesSubtotal, $lineTotal);
        }

        $grandTotal = DecimalMath::add($linesSubtotal, $packaging);

        return new CartTotalsResult($lineTotals, $linesSubtotal, $packaging, $grandTotal);
    }
}
