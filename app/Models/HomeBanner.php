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
     * One storefront button (shared label) that can target many products.
     *
     * @return array{label: array{en: string, ar: string}, url: ?string, product_ids: list<int>}|null
     */
    public function resolvedCtaButton(): ?array
    {
        $buttons = $this->resolvedCtaButtons();

        return $buttons[0] ?? null;
    }

    /**
     * @return list<array{label: array{en: string, ar: string}, url: ?string, product_ids: list<int>, product_id: ?int}>
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
        $productIds = self::productIdsFromCtaUrl($url);

        if ($url === '' && $labelEn === '' && $labelAr === '' && $productIds === []) {
            return [];
        }

        return [self::buttonPayload(['en' => $labelEn, 'ar' => $labelAr], $url !== '' ? $url : null, $productIds)];
    }

    /** @return list<int> */
    public function resolvedCtaProductIds(): array
    {
        $ids = [];
        foreach ($this->resolvedCtaButtons() as $button) {
            foreach ($button['product_ids'] as $id) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }

    /** @return Collection<int, Product> */
    public function ctaProducts(): Collection
    {
        $ids = $this->resolvedCtaProductIds();
        if ($ids === []) {
            return collect();
        }

        return Product::query()
            ->where('is_active', true)
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(function (Product $product) use ($ids) {
                $index = array_search($product->id, $ids, true);

                return $index === false ? 9999 : $index;
            })
            ->values();
    }

    /**
     * @param  array{en?: string, ar?: string}|null  $label
     * @param  list<int|string>  $productIds
     * @return list<array{label: array{en: string, ar: string}, url: ?string, product_ids: list<int>, product_id: ?int}>
     */
    public static function packCtaButtons(?array $label, mixed $url, array $productIds = []): array
    {
        $labelEn = trim((string) ($label['en'] ?? ''));
        $labelAr = trim((string) ($label['ar'] ?? ''));
        $url = is_string($url) ? trim($url) : '';
        $ids = self::normalizeProductIds($productIds);

        if ($url === '' && $ids !== []) {
            $url = (string) self::ctaUrlForProductIds($ids);
        }

        if ($url === '' && $labelEn === '' && $labelAr === '' && $ids === []) {
            return [];
        }

        if ($ids === [] && $url !== '') {
            $ids = self::productIdsFromCtaUrl($url);
        }

        return [self::buttonPayload(['en' => $labelEn, 'ar' => $labelAr], $url !== '' ? $url : null, $ids)];
    }

    /**
     * @param  list<int|string>  $productIds
     */
    public static function ctaUrlForProductIds(array $productIds): ?string
    {
        $ids = self::normalizeProductIds($productIds);
        if ($ids === []) {
            return null;
        }

        if (count($ids) === 1) {
            return route('store.product', $ids[0], absolute: false);
        }

        return route('store.shop', ['products' => implode(',', $ids)], absolute: false);
    }

    /**
     * @return list<array{label: array{en: string, ar: string}, url: ?string, product_ids: list<int>, product_id: ?int}>
     */
    public static function normalizeCtaButtons(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $labelEn = '';
        $labelAr = '';
        $url = '';
        $productIds = [];

        foreach ($value as $row) {
            if (! is_array($row)) {
                continue;
            }

            $label = is_array($row['label'] ?? null) ? $row['label'] : [];
            if ($labelEn === '') {
                $labelEn = trim((string) ($label['en'] ?? $row['label_en'] ?? ''));
            }
            if ($labelAr === '') {
                $labelAr = trim((string) ($label['ar'] ?? $row['label_ar'] ?? ''));
            }

            $rowUrl = trim((string) ($row['url'] ?? ''));
            if ($url === '' && $rowUrl !== '') {
                $url = $rowUrl;
            }

            foreach (self::normalizeProductIds($row['product_ids'] ?? []) as $id) {
                $productIds[] = $id;
            }

            if (isset($row['product_id']) && filled($row['product_id'])) {
                $productIds[] = (int) $row['product_id'];
            }

            if ($rowUrl !== '') {
                foreach (self::productIdsFromCtaUrl($rowUrl) as $id) {
                    $productIds[] = $id;
                }
            }
        }

        $productIds = self::normalizeProductIds($productIds);

        if ($url === '' && $productIds !== []) {
            $url = (string) self::ctaUrlForProductIds($productIds);
        }

        if ($url === '' && $labelEn === '' && $labelAr === '' && $productIds === []) {
            return [];
        }

        return [self::buttonPayload(['en' => $labelEn, 'ar' => $labelAr], $url !== '' ? $url : null, $productIds)];
    }

    public static function productIdFromCtaUrl(?string $url): ?int
    {
        $ids = self::productIdsFromCtaUrl($url);

        return $ids[0] ?? null;
    }

    /** @return list<int> */
    public static function productIdsFromCtaUrl(?string $url): array
    {
        if (! is_string($url) || $url === '') {
            return [];
        }

        if (preg_match('#/products/(\d+)(?:$|[/?])#', $url, $match) === 1) {
            return [(int) $match[1]];
        }

        $query = parse_url($url, PHP_URL_QUERY);
        if (! is_string($query) || $query === '') {
            return [];
        }

        parse_str($query, $params);
        $raw = $params['products'] ?? null;
        if (! is_string($raw) && ! is_numeric($raw)) {
            return [];
        }

        return self::normalizeProductIds(explode(',', (string) $raw));
    }

    /** Keep cta_buttons + legacy cta_label / cta_url aligned (Excel + older code). */
    public function syncPrimaryCtaFromButtons(): void
    {
        $existing = self::normalizeCtaButtons($this->cta_buttons);
        $productIds = $existing[0]['product_ids'] ?? [];
        if ($productIds === [] && filled($this->cta_url)) {
            $productIds = self::productIdsFromCtaUrl((string) $this->cta_url);
        }

        $label = is_array($this->cta_label) ? $this->cta_label : ($existing[0]['label'] ?? ['en' => '', 'ar' => '']);
        $url = filled($this->cta_url) ? (string) $this->cta_url : ($existing[0]['url'] ?? null);

        $buttons = self::packCtaButtons($label, $url, $productIds);
        $this->cta_buttons = $buttons === [] ? null : $buttons;

        if ($buttons === []) {
            return;
        }

        $this->cta_label = $buttons[0]['label'];
        $this->cta_url = $buttons[0]['url'];
    }

    /**
     * @param  array{en: string, ar: string}  $label
     * @param  list<int>  $productIds
     * @return array{label: array{en: string, ar: string}, url: ?string, product_ids: list<int>, product_id: ?int}
     */
    private static function buttonPayload(array $label, ?string $url, array $productIds): array
    {
        return [
            'label' => [
                'en' => trim((string) ($label['en'] ?? '')),
                'ar' => trim((string) ($label['ar'] ?? '')),
            ],
            'url' => filled($url) ? (string) $url : null,
            'product_ids' => $productIds,
            'product_id' => $productIds[0] ?? null,
        ];
    }

    /**
     * @param  list<int|string>  $productIds
     * @return list<int>
     */
    private static function normalizeProductIds(array $productIds): array
    {
        $ids = [];
        foreach ($productIds as $id) {
            if ($id === null || $id === '') {
                continue;
            }
            $ids[] = (int) $id;
        }

        return array_values(array_unique(array_filter($ids, fn (int $id): bool => $id > 0)));
    }
}
