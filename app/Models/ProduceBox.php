<?php

namespace App\Models;

use App\Support\ProduceStockPhoto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ProduceBox extends Model
{
    use HasTranslations;

    /** @var list<string> */
    public array $translatable = ['name', 'slug'];

    protected $fillable = ['name', 'slug', 'price', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:4',
        ];
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

    /** Cover photo from the first box item that has a product image. */
    public function getCoverImageUrlAttribute(): ?string
    {
        $this->loadMissing('items.product');

        foreach ($this->items as $item) {
            $url = $item->product?->display_image_url;

            if ($url) {
                return $url;
            }
        }

        return ProduceStockPhoto::urlFor('Spring mix salad', 'BOX-FALLBACK');
    }
}
