<?php

namespace App\Support;

use App\Models\PackagingType;
use App\Models\ProduceBox;
use App\Models\Product;
use App\Services\Money\DecimalMath;
use App\Services\Pricing\SurchargeOnBase;
use Illuminate\Support\Collection;

final class StoreCart
{
    public const SESSION_KEY = 'aldawy_cart';

    public const UNIT_KG = 'kg';

    public const UNIT_PIECE = 'piece';

    public const KIND_PRODUCT = 'product';

    public const KIND_BOX = 'box';

    /**
     * @return list<array{
     *     product_id: int,
     *     unit: string,
     *     quantity: string,
     *     preparation_service_ids: list<int>,
     *     packaging_type_id: int|null,
     *     unit_price_snapshot: string|null
     * }>
     */
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

    public static function hasPriceChanges(): bool
    {
        return self::priceChangedLines()->isNotEmpty();
    }

    /** @return Collection<int, array{product: Product, snapshot: string, current: string, unit: string}> */
    public static function priceChangedLines(): Collection
    {
        $lines = self::lines();
        if ($lines === []) {
            return collect();
        }

        $productLines = array_values(array_filter($lines, fn ($l) => ($l['type'] ?? self::KIND_PRODUCT) === self::KIND_PRODUCT));
        if ($productLines === []) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', array_map(fn ($l) => (int) $l['product_id'], $productLines))
            ->get()
            ->keyBy('id');

        return collect($productLines)->map(function (array $row) use ($products) {
            $snapshot = $row['unit_price_snapshot'] ?? null;
            if ($snapshot === null || $snapshot === '') {
                return null;
            }

            $product = $products->get((int) $row['product_id']);
            if (! $product instanceof Product) {
                return null;
            }

            $unit = $row['unit'];
            $current = self::unitPriceForProduct($product, $unit);
            if ($current === null) {
                return null;
            }

            $snapshot = DecimalMath::normalizeNumericString($snapshot);
            if (bccomp($snapshot, $current, 4) === 0) {
                return null;
            }

            return [
                'product' => $product,
                'snapshot' => $snapshot,
                'current' => $current,
                'unit' => $unit,
            ];
        })->filter()->values();
    }

    /**
     * @param  list<int>  $preparationServiceIds
     */
    public static function add(
        int $productId,
        string $unit,
        string $quantity,
        array $preparationServiceIds = [],
        ?int $packagingTypeId = null,
        ?string $unitPriceSnapshot = null,
    ): void {
        $unit = self::normalizeUnit($unit);
        $quantity = self::normalizeQuantity($quantity, $unit);
        if (bccomp($quantity, '0', 4) <= 0) {
            return;
        }

        $prepIds = self::normalizePrepIds($preparationServiceIds);
        $packId = self::normalizePackagingId($packagingTypeId);

        $newLine = [
            'type' => self::KIND_PRODUCT,
            'product_id' => $productId,
            'unit' => $unit,
            'quantity' => $quantity,
            'preparation_service_ids' => $prepIds,
            'packaging_type_id' => $packId,
            'unit_price_snapshot' => $unitPriceSnapshot !== null
                ? DecimalMath::normalizeNumericString($unitPriceSnapshot)
                : null,
        ];

        $lines = self::lines();
        $key = self::lineKey($newLine);
        foreach ($lines as $i => $line) {
            if (self::lineKey($line) === $key) {
                $lines[$i]['quantity'] = self::normalizeQuantity(
                    bcadd($lines[$i]['quantity'], $quantity, 4),
                    $unit,
                );
                if (($lines[$i]['unit_price_snapshot'] ?? null) === null && $newLine['unit_price_snapshot'] !== null) {
                    $lines[$i]['unit_price_snapshot'] = $newLine['unit_price_snapshot'];
                }
                session([self::SESSION_KEY => $lines]);

                return;
            }
        }

        $lines[] = $newLine;
        session([self::SESSION_KEY => $lines]);
    }

    public static function addBox(int $produceBoxId, ?string $unitPriceSnapshot = null): void
    {
        $newLine = [
            'type' => self::KIND_BOX,
            'produce_box_id' => $produceBoxId,
            'quantity' => '1',
            'unit_price_snapshot' => $unitPriceSnapshot !== null
                ? DecimalMath::normalizeNumericString($unitPriceSnapshot)
                : null,
        ];

        $lines = self::lines();
        $key = self::lineKey($newLine);
        foreach ($lines as $i => $line) {
            if (self::lineKey($line) === $key) {
                return;
            }
        }

        $lines[] = $newLine;
        session([self::SESSION_KEY => $lines]);
    }

    public static function update(int $index, string $unit, string $quantity): void
    {
        $lines = self::lines();
        if (! isset($lines[$index]) || ($lines[$index]['type'] ?? self::KIND_PRODUCT) !== self::KIND_PRODUCT) {
            return;
        }

        $unit = self::normalizeUnit($unit);
        $quantity = self::normalizeQuantity($quantity, $unit);
        if (bccomp($quantity, '0', 4) <= 0) {
            self::remove($index);

            return;
        }

        self::updateLine(
            $index,
            $unit,
            $quantity,
            $lines[$index]['preparation_service_ids'],
            $lines[$index]['packaging_type_id'] ?? null,
        );
    }

    /**
     * @param  list<int>  $preparationServiceIds
     */
    public static function updateLine(
        int $index,
        string $unit,
        string $quantity,
        array $preparationServiceIds,
        ?int $packagingTypeId,
    ): void {
        $lines = self::lines();
        if (! isset($lines[$index]) || ($lines[$index]['type'] ?? self::KIND_PRODUCT) !== self::KIND_PRODUCT) {
            return;
        }

        $unit = self::normalizeUnit($unit);
        $quantity = self::normalizeQuantity($quantity, $unit);
        if (bccomp($quantity, '0', 4) <= 0) {
            self::remove($index);

            return;
        }

        $lines[$index] = [
            'type' => self::KIND_PRODUCT,
            'product_id' => (int) $lines[$index]['product_id'],
            'unit' => $unit,
            'quantity' => $quantity,
            'preparation_service_ids' => self::normalizePrepIds($preparationServiceIds),
            'packaging_type_id' => self::normalizePackagingId($packagingTypeId),
            'unit_price_snapshot' => $lines[$index]['unit_price_snapshot'] ?? null,
        ];

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

    public static function resolved(): Collection
    {
        $lines = self::lines();
        if ($lines === []) {
            return collect();
        }

        $resolved = collect();

        $productLines = array_values(array_filter($lines, fn ($l) => ($l['type'] ?? self::KIND_PRODUCT) === self::KIND_PRODUCT));
        if ($productLines !== []) {
            $ids = array_unique(array_map(fn ($l) => (int) $l['product_id'], $productLines));
            $products = Product::query()
                ->whereIn('id', $ids)
                ->where('is_active', true)
                ->with([
                    'preparationServices' => fn ($q) => $q->where('preparation_services.is_active', true),
                    'packagingTypes' => fn ($q) => $q->where('packaging_types.is_active', true),
                ])
                ->get()
                ->keyBy('id');

            foreach ($lines as $lineIndex => $row) {
                if (($row['type'] ?? self::KIND_PRODUCT) !== self::KIND_PRODUCT) {
                    continue;
                }

                $product = $products->get((int) $row['product_id']);
                if (! $product instanceof Product) {
                    continue;
                }

                $unit = $row['unit'];
                $quantity = $row['quantity'];
                $unitPrice = self::unitPriceForProduct($product, $unit);
                if ($unitPrice === null) {
                    continue;
                }

                $prepIds = self::normalizePrepIds($row['preparation_service_ids'] ?? []);
                $packagingTypeId = self::normalizePackagingId($row['packaging_type_id'] ?? null);
                $enabledPrep = $product->preparationServices->filter(fn ($p) => (bool) $p->pivot->is_enabled);
                $enabledPack = $product->packagingTypes->filter(fn ($p) => (bool) $p->pivot->is_enabled);
                $selectedPrep = $enabledPrep->whereIn('id', $prepIds)->values();
                $packagingType = $packagingTypeId
                    ? $enabledPack->firstWhere('id', $packagingTypeId)
                    : null;

                $lineBase = DecimalMath::mul($unitPrice, $quantity);
                $servicesSurcharge = SurchargeOnBase::sumForRules($lineBase, $selectedPrep);
                $amountAfterServices = DecimalMath::add($lineBase, $servicesSurcharge);
                $packagingSurcharge = $packagingType
                    ? SurchargeOnBase::amount($amountAfterServices, $packagingType)
                    : DecimalMath::normalizeNumericString('0');
                $lineSubtotal = DecimalMath::add($amountAfterServices, $packagingSurcharge);

                $snapshot = isset($row['unit_price_snapshot']) && $row['unit_price_snapshot'] !== ''
                    ? DecimalMath::normalizeNumericString((string) $row['unit_price_snapshot'])
                    : null;

                $resolved->push([
                    'kind' => self::KIND_PRODUCT,
                    'line' => $lineIndex,
                    'product' => $product,
                    'unit' => $unit,
                    'quantity' => $quantity,
                    'preparation_service_ids' => $prepIds,
                    'packaging_type_id' => $packagingType?->id,
                    'preparation_services' => $selectedPrep,
                    'packaging_type' => $packagingType,
                    'line_base' => $lineBase,
                    'services_surcharge' => $servicesSurcharge,
                    'packaging_surcharge' => $packagingSurcharge,
                    'line_subtotal' => $lineSubtotal,
                    'unit_price_snapshot' => $snapshot,
                    'price_changed' => $snapshot !== null && bccomp($snapshot, $unitPrice, 4) !== 0,
                ]);
            }
        }

        $boxIds = [];
        foreach ($lines as $row) {
            if (($row['type'] ?? '') === self::KIND_BOX) {
                $boxIds[] = (int) $row['produce_box_id'];
            }
        }

        if ($boxIds !== []) {
            $boxes = ProduceBox::query()
                ->whereIn('id', array_unique($boxIds))
                ->where('is_active', true)
                ->with(['items.product'])
                ->get()
                ->keyBy('id');

            foreach ($lines as $lineIndex => $row) {
                if (($row['type'] ?? '') !== self::KIND_BOX) {
                    continue;
                }

                $box = $boxes->get((int) $row['produce_box_id']);
                if (! $box instanceof ProduceBox) {
                    continue;
                }

                $unitPrice = DecimalMath::normalizeNumericString((string) $box->price);
                $quantity = '1';
                $lineSubtotal = DecimalMath::mul($unitPrice, $quantity);
                $snapshot = isset($row['unit_price_snapshot']) && $row['unit_price_snapshot'] !== ''
                    ? DecimalMath::normalizeNumericString((string) $row['unit_price_snapshot'])
                    : null;

                $resolved->push([
                    'kind' => self::KIND_BOX,
                    'line' => $lineIndex,
                    'produce_box' => $box,
                    'quantity' => $quantity,
                    'line_subtotal' => $lineSubtotal,
                    'unit_price_snapshot' => $snapshot,
                    'price_changed' => $snapshot !== null && bccomp($snapshot, $unitPrice, 4) !== 0,
                    'line_base' => $lineSubtotal,
                    'services_surcharge' => '0',
                    'packaging_surcharge' => '0',
                ]);
            }
        }

        return $resolved->sortBy('line')->values();
    }

    public static function subtotal(): string
    {
        $sum = '0';
        foreach (self::resolved() as $row) {
            $sum = bcadd($sum, $row['line_subtotal'], 4);
        }

        return $sum;
    }

    public static function unitPriceForProduct(Product $product, string $unit): ?string
    {
        if ($unit === self::UNIT_PIECE) {
            if (! $product->sell_by_piece || $product->price_per_piece === null) {
                return null;
            }

            return DecimalMath::normalizeNumericString((string) $product->price_per_piece);
        }

        return DecimalMath::normalizeNumericString((string) $product->price_per_kg);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array{product_id: int, unit: string, quantity: string, preparation_service_ids: list<int>, packaging_type_id: int|null, unit_price_snapshot: string|null}|null
     */
    private static function normalizeLine(mixed $row): ?array
    {
        if (! is_array($row)) {
            return null;
        }

        $type = $row['type'] ?? (isset($row['produce_box_id']) ? self::KIND_BOX : self::KIND_PRODUCT);

        if ($type === self::KIND_BOX) {
            if (! isset($row['produce_box_id'])) {
                return null;
            }

            $snapshot = $row['unit_price_snapshot'] ?? null;
            if ($snapshot !== null && $snapshot !== '' && is_numeric((string) $snapshot)) {
                $snapshot = DecimalMath::normalizeNumericString((string) $snapshot);
            } else {
                $snapshot = null;
            }

            return [
                'type' => self::KIND_BOX,
                'produce_box_id' => (int) $row['produce_box_id'],
                'quantity' => '1',
                'unit_price_snapshot' => $snapshot,
            ];
        }

        if (! isset($row['product_id'])) {
            return null;
        }

        $unit = self::normalizeUnit($row['unit'] ?? self::UNIT_KG);
        if (isset($row['quantity'])) {
            $quantity = self::normalizeQuantity((string) $row['quantity'], $unit);
        } else {
            $quantity = self::normalizeQuantity((string) ($row['kg'] ?? '0'), $unit);
        }

        if (bccomp($quantity, '0', 4) <= 0) {
            return null;
        }

        $snapshot = $row['unit_price_snapshot'] ?? null;
        if ($snapshot !== null && $snapshot !== '' && is_numeric((string) $snapshot)) {
            $snapshot = DecimalMath::normalizeNumericString((string) $snapshot);
        } else {
            $snapshot = null;
        }

        return [
            'type' => self::KIND_PRODUCT,
            'product_id' => (int) $row['product_id'],
            'unit' => $unit,
            'quantity' => $quantity,
            'preparation_service_ids' => self::normalizePrepIds($row['preparation_service_ids'] ?? []),
            'packaging_type_id' => self::normalizePackagingId($row['packaging_type_id'] ?? null),
            'unit_price_snapshot' => $snapshot,
        ];
    }

    /** @param  array<string, mixed>  $line */
    private static function lineKey(array $line): string
    {
        if (($line['type'] ?? self::KIND_PRODUCT) === self::KIND_BOX) {
            return 'box|'.(int) $line['produce_box_id'];
        }

        $prep = $line['preparation_service_ids'] ?? [];
        sort($prep);

        return 'product|'.$line['product_id'].'|'.$line['unit'].'|'.implode(',', $prep).'|'.($line['packaging_type_id'] ?? '0');
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

    private static function normalizeUnit(mixed $unit): string
    {
        return $unit === self::UNIT_PIECE ? self::UNIT_PIECE : self::UNIT_KG;
    }

    private static function normalizeQuantity(string $qty, string $unit): string
    {
        $qty = trim($qty);
        if ($qty === '' || ! is_numeric($qty)) {
            return '0';
        }

        if ($unit === self::UNIT_PIECE) {
            return (string) max(1, (int) round((float) $qty));
        }

        return bcadd($qty, '0', 4);
    }
}
