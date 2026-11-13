<?php

namespace App\Models;

use App\Services\Money\DecimalMath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    public const UNIT_KG = 'kg';

    public const UNIT_GRAM = 'gram';

    public const UNIT_PIECE = 'piece';

    public const ORIGIN_LOCAL = 'local';

    public const ORIGIN_IMPORTED = 'imported';

    protected $attributes = [
        'unit' => self::UNIT_KG,
        'is_local' => true,
        'is_imported' => false,
    ];

    /** @var list<string> */
    public array $translatable = ['name', 'slug', 'description'];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image_url',
        'image_path',
        'sku',
        'price_per_kg',
        'price_per_gram',
        'price_per_piece',
        'unit',
        'is_local',
        'is_imported',
        'imported_price_per_kg',
        'imported_price_per_gram',
        'imported_price_per_piece',
        'sell_by_piece',
        'is_active',
        'track_stock',
        'stock_quantity',
        'min_order_quantity',
        'view_count',
    ];

    protected static function booted(): void
    {
        static::updating(function (Product $product): void {
            if (! $product->isDirty('image_path')) {
                return;
            }
            $original = $product->getOriginal('image_path');
            if ($original && $original !== $product->image_path && Storage::disk('public')->exists($original)) {
                Storage::disk('public')->delete($original);
            }
        });

        static::deleting(function (Product $product): void {
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
        });

        static::saving(function (Product $product): void {
            $unit = $product->sellingUnit();
            $product->unit = $unit;

            if ($unit === self::UNIT_PIECE) {
                $product->sell_by_piece = true;
            }

            if ($unit === self::UNIT_GRAM) {
                $product->sell_by_piece = false;
            }

            if (! $product->offersLocal() && ! $product->offersImported()) {
                $product->is_local = true;
            }
        });
    }

    /**
     * Absolute public URL: uploaded file first, otherwise the external image URL.
     * Must stay absolute — Filament's ImageColumn re-resolves relative paths against the default (private) disk.
     */
    public function getDisplayImageUrlAttribute(): ?string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return url(Storage::disk('public')->url(str_replace('\\', '/', $this->image_path)));
        }

        if (filled($this->image_url)) {
            return (string) $this->image_url;
        }

        return null;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isOutOfStock(): bool
    {
        if (! $this->track_stock) {
            return false;
        }

        $qty = $this->stock_quantity;

        return $qty === null || bccomp((string) $qty, '0', 4) <= 0;
    }

    public function hasStockFor(string $requestedQty, ?string $unit = null): bool
    {
        if (! $this->track_stock) {
            return true;
        }

        if ($this->stock_quantity === null) {
            return false;
        }

        $requested = $unit === null
            ? DecimalMath::normalizeNumericString($requestedQty)
            : $this->quantityInStockUnit($unit, $requestedQty);
        $available = DecimalMath::normalizeNumericString((string) $this->stock_quantity);

        return bccomp($available, $requested, 4) >= 0;
    }

    public function sellingUnit(): string
    {
        $unit = (string) ($this->unit ?? self::UNIT_KG);

        return in_array($unit, [self::UNIT_KG, self::UNIT_GRAM, self::UNIT_PIECE], true)
            ? $unit
            : self::UNIT_KG;
    }

    /**
     * The unit chosen in admin. A kilogram product can also offer pieces.
     *
     * @return list<string>
     */
    public function availableUnits(): array
    {
        $unit = $this->sellingUnit();
        $units = [$unit];

        if ($unit === self::UNIT_KG && $this->sell_by_piece && $this->price_per_piece !== null) {
            $units[] = self::UNIT_PIECE;
        }

        return $units;
    }

    public function allowsUnit(string $unit): bool
    {
        return in_array($unit, $this->availableUnits(), true);
    }

    public function defaultOrderUnit(): string
    {
        return $this->sellingUnit();
    }

    public function offersLocal(): bool
    {
        return (bool) ($this->is_local ?? true);
    }

    public function offersImported(): bool
    {
        return (bool) $this->is_imported;
    }

    /**
     * Local, imported, or both. Never empty: a product with neither flag is treated as local.
     *
     * @return list<string>
     */
    public function availableOrigins(): array
    {
        $origins = [];

        if ($this->offersLocal()) {
            $origins[] = self::ORIGIN_LOCAL;
        }

        if ($this->offersImported()) {
            $origins[] = self::ORIGIN_IMPORTED;
        }

        return $origins === [] ? [self::ORIGIN_LOCAL] : $origins;
    }

    public function allowsOrigin(string $origin): bool
    {
        return in_array($origin, $this->availableOrigins(), true);
    }

    public function defaultOrigin(): string
    {
        return $this->offersLocal()
            ? self::ORIGIN_LOCAL
            : self::ORIGIN_IMPORTED;
    }

    /** Convert an ordered quantity into the unit stored in stock_quantity. */
    public function quantityInStockUnit(string $unit, string $quantity): string
    {
        $quantity = DecimalMath::normalizeNumericString($quantity);
        $stockUnit = $this->sellingUnit();

        if ($unit === $stockUnit) {
            return $quantity;
        }

        if ($stockUnit === self::UNIT_KG && $unit === self::UNIT_GRAM) {
            return DecimalMath::div($quantity, '1000');
        }

        if ($stockUnit === self::UNIT_GRAM && $unit === self::UNIT_KG) {
            return DecimalMath::mul($quantity, '1000');
        }

        return $quantity;
    }

    protected function casts(): array
    {
        return [
            'sell_by_piece' => 'boolean',
            'is_active' => 'boolean',
            'is_local' => 'boolean',
            'is_imported' => 'boolean',
            'track_stock' => 'boolean',
            'stock_quantity' => 'decimal:4',
            'min_order_quantity' => 'decimal:4',
            'price_per_kg' => 'decimal:4',
            'price_per_gram' => 'decimal:4',
            'price_per_piece' => 'decimal:4',
            'imported_price_per_kg' => 'decimal:4',
            'imported_price_per_gram' => 'decimal:4',
            'imported_price_per_piece' => 'decimal:4',
            'view_count' => 'integer',
        ];
    }

    /**
     * Minimum quantity for the given unit.
     * An empty product setting falls back to 0.25 kg, 1 gram, or 1 piece on the selling unit.
     * Gram and kilogram minimums convert at 1 kg = 1,000 g.
     */
    public function minimumOrderQuantity(string $unit = 'kg'): string
    {
        $selling = $this->sellingUnit();
        $hasCustom = $this->min_order_quantity !== null
            && bccomp((string) $this->min_order_quantity, '0', 4) > 0;
        $base = $hasCustom
            ? DecimalMath::normalizeNumericString((string) $this->min_order_quantity)
            : match ($selling) {
                self::UNIT_PIECE => DecimalMath::normalizeNumericString('1'),
                self::UNIT_GRAM => DecimalMath::normalizeNumericString('1'),
                default => DecimalMath::normalizeNumericString('0.25'),
            };

        if ($unit === $selling) {
            return $base;
        }

        if ($selling === self::UNIT_KG && $unit === self::UNIT_GRAM) {
            return DecimalMath::mul($base, '1000');
        }

        if ($selling === self::UNIT_GRAM && $unit === self::UNIT_KG) {
            return DecimalMath::div($base, '1000');
        }

        return match ($unit) {
            self::UNIT_PIECE => DecimalMath::normalizeNumericString('1'),
            self::UNIT_GRAM => DecimalMath::normalizeNumericString('1'),
            default => DecimalMath::normalizeNumericString('0.25'),
        };
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = $term !== null ? trim($term) : '';
        if ($term === '') {
            return $query;
        }

        $like = '%'.addcslashes($term, '%_\\').'%';
        $lower = mb_strtolower($term);
        $likeLower = '%'.addcslashes($lower, '%_\\').'%';
        $driver = $query->getConnection()->getDriverName();

        return $query->where(function (Builder $q) use ($like, $likeLower, $driver) {
            if ($driver === 'mysql') {
                $q->whereRaw('LOWER(COALESCE(JSON_UNQUOTE(JSON_EXTRACT(`name`, "$.en")), "")) LIKE ?', [$likeLower])
                    ->orWhereRaw('LOWER(COALESCE(JSON_UNQUOTE(JSON_EXTRACT(`name`, "$.ar")), "")) LIKE ?', [$likeLower])
                    ->orWhere('sku', 'like', $like)
                    ->orWhere('description->en', 'like', $like)
                    ->orWhere('description->ar', 'like', $like);
            } elseif ($driver === 'pgsql') {
                $table = $q->getModel()->getTable();
                $q->whereRaw("LOWER(COALESCE({$table}.name->>'en', '')) LIKE ?", [$likeLower])
                    ->orWhereRaw("LOWER(COALESCE({$table}.name->>'ar', '')) LIKE ?", [$likeLower])
                    ->orWhere('sku', 'ilike', $like)
                    ->orWhereRaw("LOWER(COALESCE({$table}.description->>'en', '')) LIKE ?", [$likeLower])
                    ->orWhereRaw("LOWER(COALESCE({$table}.description->>'ar', '')) LIKE ?", [$likeLower]);
            } else {
                $q->whereRaw('LOWER(COALESCE(json_extract(name, "$.en"), "")) LIKE ?', [$likeLower])
                    ->orWhereRaw('LOWER(COALESCE(json_extract(name, "$.ar"), "")) LIKE ?', [$likeLower])
                    ->orWhere('sku', 'like', $like)
                    ->orWhereRaw('LOWER(COALESCE(json_extract(description, "$.en"), "")) LIKE ?', [$likeLower])
                    ->orWhereRaw('LOWER(COALESCE(json_extract(description, "$.ar"), "")) LIKE ?', [$likeLower]);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function preparationServices(): BelongsToMany
    {
        return $this->belongsToMany(PreparationService::class)
            ->withPivot('is_enabled');
    }

    public function packagingTypes(): BelongsToMany
    {
        return $this->belongsToMany(PackagingType::class)
            ->withPivot('is_enabled');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productViews(): HasMany
    {
        return $this->hasMany(ProductView::class);
    }
}
