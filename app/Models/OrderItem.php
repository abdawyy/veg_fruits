<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'produce_box_id',
        'product_name_snapshot',
        'unit',
        'quantity',
        'services',
        'packaging',
        'unit_price',
        'line_total',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'product_name_snapshot' => 'array',
            'services' => 'array',
            'metadata' => 'array',
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'line_total' => 'decimal:4',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function produceBox(): BelongsTo
    {
        return $this->belongsTo(ProduceBox::class);
    }

    public function productLabel(): string
    {
        $names = $this->filledTranslations($this->product_name_snapshot)
            ?: $this->filledTranslations($this->product?->getTranslations('name'))
            ?: $this->filledTranslations($this->produceBox?->getTranslations('name'));

        if ($names === []) {
            return '—';
        }

        $preferred = $names[app()->getLocale()] ?? $names['en'] ?? reset($names);
        $secondary = [];

        foreach (['en', 'ar'] as $locale) {
            $value = $names[$locale] ?? null;
            if (is_string($value) && $value !== '' && $value !== $preferred && ! in_array($value, $secondary, true)) {
                $secondary[] = $value;
            }
        }

        return $secondary === [] ? $preferred : $preferred.' — '.implode(' — ', $secondary);
    }

    /**
     * @return array<string, string>
     */
    private function filledTranslations(mixed $state): array
    {
        if (is_string($state) && $state !== '') {
            return ['en' => $state];
        }

        if (! is_array($state)) {
            return [];
        }

        $names = [];

        foreach ($state as $key => $value) {
            if (is_string($value) && $value !== '') {
                $names[(string) $key] = $value;
            }
        }

        return $names;
    }
}
