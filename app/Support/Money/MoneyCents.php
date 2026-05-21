<?php

namespace App\Support\Money;

use App\Services\Money\DecimalMath;

/** Display money as integer minor units (e.g. piastres) for safe client-side math. */
final class MoneyCents
{
    public static function fromDecimal(string $amount, int $minorScale = 2): int
    {
        $normalized = DecimalMath::normalizeNumericString($amount);
        $multiplier = bcpow('10', (string) $minorScale, 0);

        return (int) bcmul($normalized, $multiplier, 0);
    }

    public static function toDecimal(int $cents, int $minorScale = 2): string
    {
        $multiplier = bcpow('10', (string) $minorScale, 0);

        return bcdiv((string) $cents, $multiplier, $minorScale);
    }
}
