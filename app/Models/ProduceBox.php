<?php

namespace App\Models;

use App\Support\ProduceStockPhoto;
use App\Support\StoreCart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class ProduceBox extends Model
{
    use HasTranslations;

    /** @var list<string> */
    public array $translatable = ['name', 'slug', 'description', 'meta_title', 'meta_description'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'image_path',
        'price',
        'compare_at_price',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::updating(function (ProduceBox $box): void {
            if (! $box->isDirty('image_path')) {
                return;
            }

            $original = $box->getOriginal('image_path');
            if ($original && $original !== $box->image_path && Storage::disk('public')->exists($original)) {
                Storage::disk('public')->delete($original);
            }
        });

        static::deleting(function (ProduceBox $box): void {
            if ($box->image_path && Storage::disk('public')->exists($box->image_path)) {
                Storage::disk('public')->delete($box->image_path);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:4',
            'compare_at_price' => 'decimal:4',
        ];
    }

    /** Tomato 5 kg, Cucumber 3 kg — built from the selected products. */
    public function contentsLabel(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $this->loadMissing('items.product');

        return $this->items
            ->filter(fn (ProduceBoxItem $item): bool => $item->product !== null)
            ->map(function (ProduceBoxItem $item) use ($locale): string {
                $name = $item->product->getTranslation('name', $locale);
                $quantity = StoreCart::formatQuantity((string) $item->quantity, (string) $item->unit);

                return trim($name.' '.$quantity.' '.StoreCart::unitLabel((string) $item->unit));
            })
            ->implode(', ');
    }

    public function hasSalePrice(): bool
    {
        if ($this->compare_at_price === null) {
            return false;
        }

        return bccomp((string) $this->compare_at_price, (string) $this->price, 4) > 0;
    }

    public function seoTitle(): string
    {
        $locale = app()->getLocale();
        $custom = trim((string) $this->getTranslation('meta_title', $locale, false));

        return $custom !== '' ? $custom : (string) $this->getTranslation('name', $locale);
    }

    public function seoDescription(): string
    {
        $locale = app()->getLocale();
        $custom = trim((string) $this->getTranslation('meta_description', $locale, false));
        if ($custom !== '') {
            return $custom;
        }

        $description = trim(strip_tags((string) $this->getTranslation('description', $locale, false)));
        if ($description !== '') {
            return mb_substr($description, 0, 300);
        }

        return mb_substr($this->contentsLabel($locale), 0, 300);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProduceBoxItem::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Cover photo: uploaded box image, otherwise the first product image. */
    public function getCoverImageUrlAttribute(): ?string
    {
        $uploaded = $this->publicFileUrl($this->image_path);
        if ($uploaded !== null) {
            return $uploaded;
        }

        $this->loadMissing('items.product');

        foreach ($this->items as $item) {
            $url = $item->product?->display_image_url;

            if ($url) {
                return $url;
            }
        }

        return ProduceStockPhoto::urlFor('Spring mix salad', 'BOX-FALLBACK');
    }

    private function publicFileUrl(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return url(Storage::disk('public')->url(str_replace('\\', '/', $path)));
    }
}
