<?php

namespace App\Services\Coupons;

use App\Models\Coupon;
use App\Services\Money\DecimalMath;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

final class ApplyCouponService
{
    /**
     * @return array{coupon: Coupon, discount: string}
     */
    public function execute(string $code, string $linesSubtotal): array
    {
        $code = strtoupper(trim($code));
        if ($code === '') {
            throw ValidationException::withMessages([
                'coupon_code' => [__('aldawy.coupon_invalid')],
            ]);
        }

        $coupon = Coupon::query()
            ->active()
            ->whereRaw('UPPER(code) = ?', [$code])
            ->first();

        if (! $coupon) {
            throw ValidationException::withMessages([
                'coupon_code' => [__('aldawy.coupon_invalid')],
            ]);
        }

        $now = Carbon::now();
        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            throw ValidationException::withMessages([
                'coupon_code' => [__('aldawy.coupon_not_started')],
            ]);
        }
        if ($coupon->ends_at && $coupon->ends_at->isPast()) {
            throw ValidationException::withMessages([
                'coupon_code' => [__('aldawy.coupon_expired')],
            ]);
        }
        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            throw ValidationException::withMessages([
                'coupon_code' => [__('aldawy.coupon_exhausted')],
            ]);
        }

        $subtotal = DecimalMath::normalizeNumericString($linesSubtotal);
        if ($coupon->min_subtotal !== null) {
            $min = DecimalMath::normalizeNumericString((string) $coupon->min_subtotal);
            if (bccomp($subtotal, $min, 4) < 0) {
                throw ValidationException::withMessages([
                    'coupon_code' => [__('aldawy.coupon_min_subtotal', [
                        'min' => number_format((float) $min, 2),
                        'currency' => config('aldawy.currency', 'EGP'),
                    ])],
                ]);
            }
        }

        $discount = match ($coupon->type) {
            Coupon::TYPE_PERCENT => $this->percentDiscount($subtotal, (string) $coupon->value),
            Coupon::TYPE_FIXED => DecimalMath::normalizeNumericString((string) $coupon->value),
            default => '0',
        };

        if (bccomp($discount, $subtotal, 4) > 0) {
            $discount = $subtotal;
        }

        if (bccomp($discount, '0', 4) <= 0) {
            throw ValidationException::withMessages([
                'coupon_code' => [__('aldawy.coupon_invalid')],
            ]);
        }

        return ['coupon' => $coupon, 'discount' => $discount];
    }

    private function percentDiscount(string $subtotal, string $percent): string
    {
        $pct = DecimalMath::normalizeNumericString($percent);
        $ratio = bcdiv($pct, '100', DecimalMath::scale());

        return DecimalMath::mul($subtotal, $ratio);
    }
}
