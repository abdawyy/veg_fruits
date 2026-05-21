<?php

namespace App\Models;

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
        'price_per_piece',
        'sell_by_piece',
        'is_active',
        'track_stock',
        'stock_quantity',
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
    }

    /** Public URL for storefront / admin preview: uploaded file wins over external URL. */
    public function getDisplayImageUrlAttribute(): ?string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }

        return $this->image_url ?: null;
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

    public function hasStockFor(string $requestedQty): bool
    {
        if (! $this->track_stock) {
            return true;
        }

        if ($this->stock_quantity === null) {
            return false;
        }

        $requested = \App\Services\Money\DecimalMath::normalizeNumericString($requestedQty);
        $available = \App\Services\Money\DecimalMath::normalizeNumericString((string) $this->stock_quantity);

        return bccomp($available, $requested, 4) >= 0;
    }

    protected function casts(): array
    {
        return [
            'sell_by_piece' => 'boolean',
            'is_active' => 'boolean',
            'track_stock' => 'boolean',
            'stock_quantity' => 'decimal:4',
            'price_per_kg' => 'decimal:4',
            'price_per_piece' => 'decimal:4',
            'view_count' => 'integer',
        ];
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
}
