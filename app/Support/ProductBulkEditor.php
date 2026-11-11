<?php

namespace App\Support;

use App\Models\Product;
use App\Services\Money\DecimalMath;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class ProductBulkEditor
{
    /**
     * @param  Collection<int, Product>  $products
     */
    public static function applyPrice(Collection $products, ?string $pricePerKg, ?string $pricePerPiece): int
    {
        $updated = 0;

        DB::transaction(function () use ($products, $pricePerKg, $pricePerPiece, &$updated): void {
            foreach ($products as $product) {
                $dirty = false;

                if ($pricePerKg !== null && $pricePerKg !== '') {
                    $product->price_per_kg = DecimalMath::normalizeNumericString($pricePerKg);
                    $dirty = true;
                }

                if ($pricePerPiece !== null && $pricePerPiece !== '') {
                    $product->price_per_piece = DecimalMath::normalizeNumericString($pricePerPiece);
                    $dirty = true;
                }

                if ($dirty) {
                    $product->save();
                    $updated++;
                }
            }
        });

        return $updated;
    }

    /**
     * @param  Collection<int, Product>  $products
     * @param  'percent'|'fixed'  $mode
     * @param  'kg'|'piece'|'both'  $target
     */
    public static function applyDiscount(Collection $products, string $mode, string $value, string $target = 'both'): int
    {
        $amount = DecimalMath::normalizeNumericString($value);
        if (bccomp($amount, '0', 4) <= 0) {
            return 0;
        }

        $updated = 0;

        DB::transaction(function () use ($products, $mode, $amount, $target, &$updated): void {
            foreach ($products as $product) {
                $dirty = false;

                if ($target === 'kg' || $target === 'both') {
                    $next = self::discountedPrice((string) $product->price_per_kg, $mode, $amount);
                    if ($next !== null) {
                        $product->price_per_kg = $next;
                        $dirty = true;
                    }
                }

                if (($target === 'piece' || $target === 'both') && $product->price_per_piece !== null) {
                    $next = self::discountedPrice((string) $product->price_per_piece, $mode, $amount);
                    if ($next !== null) {
                        $product->price_per_piece = $next;
                        $dirty = true;
                    }
                }

                if ($dirty) {
                    $product->save();
                    $updated++;
                }
            }
        });

        return $updated;
    }

    /**
     * @param  Collection<int, Product>  $products
     */
    public static function setActive(Collection $products, bool $active): int
    {
        $ids = $products->modelKeys();
        if ($ids === []) {
            return 0;
        }

        return Product::query()->whereIn('id', $ids)->update(['is_active' => $active]);
    }

    /**
     * @param  Collection<int, Product>  $products
     */
    public static function setMinOrderQuantity(Collection $products, ?string $quantity): int
    {
        $normalized = ($quantity === null || $quantity === '')
            ? null
            : DecimalMath::normalizeNumericString($quantity);

        if ($normalized !== null && bccomp($normalized, '0', 4) <= 0) {
            $normalized = null;
        }

        $updated = 0;

        DB::transaction(function () use ($products, $normalized, &$updated): void {
            foreach ($products as $product) {
                $product->min_order_quantity = $normalized;
                $product->save();
                $updated++;
            }
        });

        return $updated;
    }

    /**
     * @param  Collection<int, Product>  $products
     * @param  'kg'|'piece'  $mode
     */
    public static function setSellUnit(Collection $products, string $mode, ?string $pricePerPiece = null): int
    {
        $updated = 0;

        DB::transaction(function () use ($products, $mode, $pricePerPiece, &$updated): void {
            foreach ($products as $product) {
                if ($mode === 'piece') {
                    $product->sell_by_piece = true;
                    if ($pricePerPiece !== null && $pricePerPiece !== '') {
                        $product->price_per_piece = DecimalMath::normalizeNumericString($pricePerPiece);
                    } elseif ($product->price_per_piece === null) {
                        $product->price_per_piece = $product->price_per_kg;
                    }
                } else {
                    $product->sell_by_piece = false;
                }

                $product->save();
                $updated++;
            }
        });

        return $updated;
    }

    private static function discountedPrice(string $current, string $mode, string $amount): ?string
    {
        $current = DecimalMath::normalizeNumericString($current);

        if ($mode === 'percent') {
            $factor = DecimalMath::sub('1', DecimalMath::mul($amount, '0.01'));
            if (bccomp($factor, '0', 4) < 0) {
                $factor = '0';
            }
            $next = DecimalMath::mul($current, $factor);
        } else {
            $next = DecimalMath::sub($current, $amount);
            if (bccomp($next, '0', 4) < 0) {
                $next = DecimalMath::normalizeNumericString('0');
            }
        }

        return $next;
    }
}
