<?php

namespace App\Services\Money;

use InvalidArgumentException;

final class DecimalMath
{
    public static function scale(): int
    {
        return max(0, (int) config('aldawy.decimal_scale', 4));
    }

    public static function add(string $a, string $b, ?int $scale = null): string
    {
        $scale ??= self::scale();

        return bcadd($a, $b, $scale);
    }

    public static function sub(string $a, string $b, ?int $scale = null): string
    {
        $scale ??= self::scale();

        return bcsub($a, $b, $scale);
    }

    public static function mul(string $a, string $b, ?int $scale = null): string
    {
        $scale ??= self::scale();

        return bcmul($a, $b, $scale);
    }

    public static function div(string $a, string $b, ?int $scale = null): string
    {
        $scale ??= self::scale();
        if (bccomp($b, '0', $scale) === 0) {
            throw new InvalidArgumentException('Division by zero.');
        }

        return bcdiv($a, $b, $scale);
    }

    public static function compare(string $a, string $b, ?int $scale = null): int
    {
        $scale ??= self::scale();

        return bccomp($a, $b, $scale);
    }

    public static function normalizeNumericString(string|int|float $value, ?int $scale = null): string
    {
        $scale ??= self::scale();
        if (is_string($value)) {
            $value = trim($value);
        }

        return bcadd((string) $value, '0', $scale);
    }
}
