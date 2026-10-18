<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class HomeBanner extends Model
{
    protected $fillable = [
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
        'title',
        'subtitle',
        'badge_text',
        'cta_label',
        'cta_url',
        'cta_buttons',
        'image_url',
        'image_path',
        'gradient_from',
        'gradient_mid',
        'gradient_to',
        'hot_product_skus',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'title' => 'array',
            'subtitle' => 'array',
            'badge_text' => 'array',
            'cta_label' => 'array',
            'cta_buttons' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (HomeBanner $banner): void {
            $banner->syncPrimaryCtaFromButtons();
        });

        static::creating(function (HomeBanner $banner): void {
            $banner->badge_text ??= ['en' => '', 'ar' => ''];
            $banner->cta_label ??= ['en' => '', 'ar' => ''];
            $banner->title ??= ['en' => '', 'ar' => ''];
            $banner->subtitle ??= ['en' => '', 'ar' => ''];
        });

        static::updating(function (HomeBanner $banner): void {
            if ($banner->isDirty('image_path')) {
                $old = $banner->getOriginal('image_path');
                if (is_string($old) && $old !== '' && $old !== $banner->image_path) {
                    Storage::disk('public')->delete($old);
                }
            }
        });

        static::deleting(function (HomeBanner $banner): void {
            $p = $banner->getRawOriginal('image_path') ?? $banner->image_path;
            if (is_string($p) && $p !== '') {
                Storage::disk('public')->delete($p);
            }
        });
    }

    /** Public URL for uploaded banner image, or null. */
    public function uploadedImagePublicUrl(): ?string
    {
        $p = is_string($this->image_path) ? str_replace('\\', '/', $this->image_path) : '';
        if ($p === '' || ! Storage::disk('public')->exists($p)) {
            return null;
        }

        return url(Storage::disk('public')->url($p));
    }

    /** Prefer uploaded file over external URL. */
    public function displayImageUrl(): ?string
    {
        return $this->uploadedImagePublicUrl()
            ?? (is_string($this->image_url) && $this->image_url !== '' ? $this->image_url : null);
    }

    /** @return list<string> */
    public function hotSkusArray(): array
    {
        $s = trim((string) ($this->attributes['hot_product_skus'] ?? ''));

        if ($s === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $s))));
    }

    public function scopeActive(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function gradientCss(): string
    {
        $from = $this->gradient_from ?: '#f97316';
        $mid = $this->gradient_mid ?: '#eab308';
        $to = $this->gradient_to ?: '#22c55e';

        return "linear-gradient(135deg, {$from}, {$mid}, {$to})";
    }

    /** @return Collection<int, Product> */
    public function hotProducts(): Collection
    {
        $skus = $this->hotSkusArray();
        if ($skus === []) {
            return collect();
        }

        return Product::query()
            ->where('is_active', true)
            ->whereIn('sku', $skus)
            ->get()
            ->sortBy(function ($p) use ($skus) {
                $i = array_search($p->sku, $skus, true);

                return $i === false ? 9999 : $i;
            })
            ->values();
    }

    /**
     * Buttons shown on the storefront. Falls back to the legacy single CTA.
     *
     * @return list<array{label: array{en: string, ar: string}, url: ?string, product_id: ?int}>
     */
    public function resolvedCtaButtons(): array
    {
        $buttons = self::normalizeCtaButtons($this->cta_buttons);
        if ($buttons !== []) {
            return $buttons;
        }

        $label = is_array($this->cta_label) ? $this->cta_label : [];
        $url = is_string($this->cta_url) ? trim($this->cta_url) : '';
        $labelEn = trim((string) ($label['en'] ?? ''));
        $labelAr = trim((string) ($label['ar'] ?? ''));

        if ($url === '' && $labelEn === '' && $labelAr === '') {
            return [];
        }

        return [[
            'label' => ['en' => $labelEn, 'ar' => $labelAr],
            'url' => $url !== '' ? $url : null,
            'product_id' => self::productIdFromCtaUrl($url),
        ]];
    }

    /**
     * @return list<array{label: array{en: string, ar: string}, url: ?string, product_id: ?int}>
     */
    public static function normalizeCtaButtons(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $buttons = [];
        foreach ($value as $row) {
            if (! is_array($row)) {
                continue;
            }

            $label = is_array($row['label'] ?? null) ? $row['label'] : [];
            $labelEn = trim((string) ($label['en'] ?? $row['label_en'] ?? ''));
            $labelAr = trim((string) ($label['ar'] ?? $row['label_ar'] ?? ''));
            $url = trim((string) ($row['url'] ?? ''));
            $productId = isset($row['product_id']) && filled($row['product_id']) ? (int) $row['product_id'] : null;

            if ($productId === null && $url !== '') {
                $productId = self::productIdFromCtaUrl($url);
            }

            if ($url === '' && $productId === null && $labelEn === '' && $labelAr === '') {
                continue;
            }

            $buttons[] = [
                'label' => ['en' => $labelEn, 'ar' => $labelAr],
                'url' => $url !== '' ? $url : null,
                'product_id' => $productId,
            ];
        }

        return array_values($buttons);
    }

    public static function productIdFromCtaUrl(?string $url): ?int
    {
        if (! is_string($url) || $url === '') {
            return null;
        }

        if (preg_match('#/products/(\d+)(?:$|[/?])#', $url, $match) === 1) {
            return (int) $match[1];
        }

        return null;
    }

    /** Keep legacy cta_label / cta_url in sync with the first button (Excel + older code). */
    public function syncPrimaryCtaFromButtons(): void
    {
        $buttons = self::normalizeCtaButtons($this->cta_buttons);

        if ($buttons === []) {
            $legacy = $this->resolvedCtaButtons();
            if ($legacy !== []) {
                $this->cta_buttons = $legacy;
                $buttons = $legacy;
            }
        } else {
            $this->cta_buttons = $buttons;
        }

        $first = $buttons[0] ?? null;
        $this->cta_label = $first['label'] ?? ['en' => '', 'ar' => ''];
        $this->cta_url = $first['url'] ?? null;
    }
}
