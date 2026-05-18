<?php

namespace App\Services\Pricing;

use App\Models\PackagingType;
use App\Models\PreparationService;
use App\Services\Money\DecimalMath;

final class SurchargeOnBase
{
    /**
     * Apply a preparation or packaging surcharge rule to a monetary base (e.g. kg × price).
     */
    public static function amount(string $base, PreparationService|PackagingType $rule): string
    {
        $base = DecimalMath::normalizeNumericString($base);
        $amount = DecimalMath::normalizeNumericString((string) $rule->surcharge_amount);

        if ($rule->surcharge_is_percent) {
            return DecimalMath::mul($base, DecimalMath::div($amount, '100'));
        }

        return $amount;
    }

    /**
     * Sum surcharges for multiple rules against the same base.
     *
     * @param  iterable<PreparationService|PackagingType>  $rules
     */
    public static function sumForRules(string $base, iterable $rules): string
    {
        $sum = DecimalMath::normalizeNumericString('0');
        foreach ($rules as $rule) {
            $sum = DecimalMath::add($sum, self::amount($base, $rule));
        }

        return $sum;
    }
}
