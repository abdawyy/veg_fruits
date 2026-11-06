<?php

namespace App\Support;

use App\Services\Money\DecimalMath;

/** Store-wide minimum product subtotal. Delivery and coupons do not count. */
final class MinimumOrder
{
    public static function amount(): string
    {
        $raw = config('aldawy.min_order_subtotal');
        if ($raw === null || $raw === '') {
            return DecimalMath::normalizeNumericString('0');
        }

        $amount = DecimalMath::normalizeNumericString((string) $raw);

        return bccomp($amount, '0', 4) < 0
            ? DecimalMath::normalizeNumericString('0')
            : $amount;
    }

    public static function applies(): bool
    {
        return bccomp(self::amount(), '0', 4) > 0;
    }

    /** How much more product subtotal is required, or null when the cart may check out. */
    public static function shortfall(string $subtotal): ?string
    {
        if (! self::applies()) {
            return null;
        }

        $subtotal = DecimalMath::normalizeNumericString($subtotal);
        $minimum = self::amount();

        if (bccomp($subtotal, $minimum, 4) >= 0) {
            return null;
        }

        return DecimalMath::sub($minimum, $subtotal);
    }

    /**
     * @return array{remaining: string, minimum: string, currency: string}
     */
    public static function messageReplacements(string $shortfall): array
    {
        return [
            'remaining' => number_format((float) $shortfall, 2),
            'minimum' => number_format((float) self::amount(), 2),
            'currency' => (string) config('aldawy.currency', 'EGP'),
        ];
    }
}
