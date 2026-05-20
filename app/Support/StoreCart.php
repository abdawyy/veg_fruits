<?php

namespace App\Support;

use App\Models\PackagingType;
use App\Models\Product;
use App\Services\Pricing\SurchargeOnBase;
use App\Services\Money\DecimalMath;
use Illuminate\Support\Collection;

final class StoreCart
{
    public const SESSION_KEY = 'aldawy_cart';

    /** @return list<array{product_id: int, kg: string, preparation_service_ids: list<int>, packaging_type_id: int|null}> */
    public static function lines(): array
    {
        $raw = session(self::SESSION_KEY, []);

        if (! is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $row) {
            $line = self::normalizeLine($row);
            if ($line !== null) {
                $out[] = $line;
            }
        }

        return array_values($out);
    }

    public static function lineCount(): int
    {
        return count(self::lines());
    }

    public static function hasDroppedLines(): bool
    {
        return self::lineCount() > self::resolved()->count();
    }

    /**
     * @param  list<int>  $preparationServiceIds
     */
    public static function add(int $productId, string $kg, array $preparationServiceIds = [], ?int $packagingTypeId = null): void
    {
        $kg = self::normalizeKg($kg);
        if (bccomp($kg, '0', 4) <= 0) {
            return;
        }

        $prepIds = self::normalizePrepIds($preparationServiceIds);
        $packId = self::normalizePackagingId($packagingTypeId);

        $newLine = [
            'product_id' => $productId,
            'kg' => $kg,
            'preparation_service_ids' => $prepIds,
            'packaging_type_id' => $packId,
        ];

        $lines = self::lines();
        $key = self::lineKey($newLine);
        foreach ($lines as $i => $line) {
            if (self::lineKey($line) === $key) {
                $lines[$i]['kg'] = self::normalizeKg(bcadd($lines[$i]['kg'], $kg, 4));
                session([self::SESSION_KEY => $lines]);

                return;
            }
        }

        $lines[] = $newLine;
        session([self::SESSION_KEY => $lines]);
    }

    public static function update(int $index, string $kg): void
    {
        $lines = self::lines();
        if (! isset($lines[$index])) {
            return;
        }

        $kg = self::normalizeKg($kg);
        if (bccomp($kg, '0', 4) <= 0) {
            self::remove($index);

            return;
        }

        $lines[$index]['kg'] = $kg;
        session([self::SESSION_KEY => array_values($lines)]);
    }

    public static function remove(int $index): void
    {
        $lines = self::lines();
        unset($lines[$index]);
        session([self::SESSION_KEY => array_values($lines)]);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * @return Collection<int, array{
     *     line: int,
     *     product: Product,
     *     kg: string,
     *     preparation_service_ids: list<int>,
     *     packaging_type_id: int|null,
     *     preparation_services: Collection<int, \App\Models\PreparationService>,
     *     packaging_type: PackagingType|null,
     *     line_base: string,
     *     services_surcharge: string,
     *     packaging_surcharge: string,
     *     line_subtotal: string
     * }>
     */
    public static function resolved(): Collection
    {
        $lines = self::lines();
        if ($lines === []) {
            return collect();
        }

        $ids = array_unique(array_map(fn ($l) => (int) $l['product_id'], $lines));
        $products = Product::query()
            ->whereIn('id', $ids)
            ->where('is_active', true)
            ->with([
                'preparationServices' => fn ($q) => $q->where('preparation_services.is_active', true),
                'packagingTypes' => fn ($q) => $q->where('packaging_types.is_active', true),
            ])
            ->get()
            ->keyBy('id');

        return collect($lines)->map(function (array $row, int $lineIndex) use ($products) {
            $product = $products->get((int) $row['product_id']);
            if (! $product instanceof Product) {
                return null;
            }

            $kg = $row['kg'];
            $prepIds = self::normalizePrepIds($row['preparation_service_ids'] ?? []);
            $packagingTypeId = self::normalizePackagingId($row['packaging_type_id'] ?? null);

            $enabledPrep = $product->preparationServices->filter(fn ($p) => (bool) $p->pivot->is_enabled);
            $enabledPack = $product->packagingTypes->filter(fn ($p) => (bool) $p->pivot->is_enabled);

            $selectedPrep = $enabledPrep->whereIn('id', $prepIds)->values();
            $packagingType = $packagingTypeId
                ? $enabledPack->firstWhere('id', $packagingTypeId)
                : null;

            $unitPrice = DecimalMath::normalizeNumericString((string) $product->price_per_kg);
            $lineBase = DecimalMath::mul($unitPrice, $kg);

            $servicesSurcharge = SurchargeOnBase::sumForRules($lineBase, $selectedPrep);

            $amountAfterServices = DecimalMath::add($lineBase, $servicesSurcharge);
            $packagingSurcharge = $packagingType
                ? SurchargeOnBase::amount($amountAfterServices, $packagingType)
                : DecimalMath::normalizeNumericString('0');

            $lineSubtotal = DecimalMath::add($amountAfterServices, $packagingSurcharge);

            return [
                'line' => $lineIndex,
                'product' => $product,
                'kg' => $kg,
                'preparation_service_ids' => $prepIds,
                'packaging_type_id' => $packagingType?->id,
                'preparation_services' => $selectedPrep,
                'packaging_type' => $packagingType,
                'line_base' => $lineBase,
                'services_surcharge' => $servicesSurcharge,
                'packaging_surcharge' => $packagingSurcharge,
                'line_subtotal' => $lineSubtotal,
            ];
        })->filter()->values();
    }

    public static function subtotal(): string
    {
        $sum = '0';
        foreach (self::resolved() as $row) {
            $sum = bcadd($sum, $row['line_subtotal'], 4);
        }

        return $sum;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array{product_id: int, kg: string, preparation_service_ids: list<int>, packaging_type_id: int|null}|null
     */
    private static function normalizeLine(mixed $row): ?array
    {
        if (! is_array($row) || ! isset($row['product_id'])) {
            return null;
        }

        $kg = self::normalizeKg((string) ($row['kg'] ?? '0'));
        if (bccomp($kg, '0', 4) <= 0) {
            return null;
        }

        return [
            'product_id' => (int) $row['product_id'],
            'kg' => $kg,
            'preparation_service_ids' => self::normalizePrepIds($row['preparation_service_ids'] ?? []),
            'packaging_type_id' => self::normalizePackagingId($row['packaging_type_id'] ?? null),
        ];
    }

    /** @param  array{product_id: int, kg: string, preparation_service_ids: list<int>, packaging_type_id: int|null}  $line */
    private static function lineKey(array $line): string
    {
        $prep = $line['preparation_service_ids'];
        sort($prep);

        return $line['product_id'].'|'.implode(',', $prep).'|'.($line['packaging_type_id'] ?? '0');
    }

    /** @return list<int> */
    private static function normalizePrepIds(mixed $ids): array
    {
        if (! is_array($ids)) {
            return [];
        }
        $out = array_values(array_unique(array_map('intval', array_filter($ids, fn ($v) => is_numeric($v)))));
        sort($out);

        return $out;
    }

    private static function normalizePackagingId(mixed $id): ?int
    {
        if ($id === null || $id === '' || $id === false) {
            return null;
        }
        $n = (int) $id;

        return $n > 0 ? $n : null;
    }

    private static function normalizeKg(string $kg): string
    {
        $kg = trim($kg);
        if ($kg === '' || ! is_numeric($kg)) {
            return '0';
        }

        return bcadd($kg, '0', 4);
    }
}
