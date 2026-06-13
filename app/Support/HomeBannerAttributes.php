<?php

namespace App\Support;

use Carbon\Carbon;

final class HomeBannerAttributes
{
    /**
     * @param  array<string, mixed>  $row  Filament form / repeater row
     * @return array<string, mixed>
     */
    public static function fromFormRow(array $row, int $defaultSortOrder = 0): array
    {
        return [
            'sort_order' => (int) ($row['sort_order'] ?? $defaultSortOrder),
            'is_active' => (bool) ($row['is_active'] ?? true),
            'starts_at' => $row['starts_at'] ?? null,
            'ends_at' => $row['ends_at'] ?? null,
            'title' => [
                'en' => (string) data_get($row, 'title.en', ''),
                'ar' => (string) data_get($row, 'title.ar', ''),
            ],
            'subtitle' => [
                'en' => (string) data_get($row, 'subtitle.en', ''),
                'ar' => (string) data_get($row, 'subtitle.ar', ''),
            ],
            'badge_text' => [
                'en' => (string) data_get($row, 'badge_text.en', ''),
                'ar' => (string) data_get($row, 'badge_text.ar', ''),
            ],
            'cta_label' => [
                'en' => (string) data_get($row, 'cta_label.en', ''),
                'ar' => (string) data_get($row, 'cta_label.ar', ''),
            ],
            'cta_url' => filled($row['cta_url'] ?? null) ? (string) $row['cta_url'] : null,
            'image_url' => filled($row['image_url'] ?? null) ? (string) $row['image_url'] : null,
            'image_path' => self::normalizeUploadedPath($row['image_path'] ?? null),
            'hot_product_skus' => filled($row['hot_product_skus'] ?? null) ? (string) $row['hot_product_skus'] : null,
            'gradient_from' => (string) ($row['gradient_from'] ?? '#f97316'),
            'gradient_mid' => (string) ($row['gradient_mid'] ?? '#eab308'),
            'gradient_to' => (string) ($row['gradient_to'] ?? '#22c55e'),
        ];
    }

    /**
     * @param  array<string, mixed>  $row  Spreadsheet row (heading row keys)
     * @return array<string, mixed>
     */
    public static function fromImportRow(array $row, int $defaultSortOrder = 0): array
    {
        $titleEn = trim((string) ($row['title_en'] ?? ''));
        $titleAr = trim((string) ($row['title_ar'] ?? ''));

        return [
            'sort_order' => isset($row['sort_order']) && $row['sort_order'] !== ''
                ? (int) $row['sort_order']
                : $defaultSortOrder,
            'is_active' => ! isset($row['is_active'])
                || filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN)
                || (string) $row['is_active'] === '1',
            'starts_at' => self::parseDate($row['starts_at'] ?? null),
            'ends_at' => self::parseDate($row['ends_at'] ?? null),
            'title' => ['en' => $titleEn, 'ar' => $titleAr],
            'subtitle' => [
                'en' => trim((string) ($row['subtitle_en'] ?? '')),
                'ar' => trim((string) ($row['subtitle_ar'] ?? '')),
            ],
            'badge_text' => [
                'en' => trim((string) ($row['badge_en'] ?? '')),
                'ar' => trim((string) ($row['badge_ar'] ?? '')),
            ],
            'cta_label' => [
                'en' => trim((string) ($row['cta_label_en'] ?? '')),
                'ar' => trim((string) ($row['cta_label_ar'] ?? '')),
            ],
            'cta_url' => filled($row['cta_url'] ?? null) ? (string) $row['cta_url'] : null,
            'image_url' => filled($row['image_url'] ?? null) ? (string) $row['image_url'] : null,
            'hot_product_skus' => filled($row['hot_product_skus'] ?? null) ? (string) $row['hot_product_skus'] : null,
            'gradient_from' => filled($row['gradient_from'] ?? null) ? (string) $row['gradient_from'] : '#f97316',
            'gradient_mid' => filled($row['gradient_mid'] ?? null) ? (string) $row['gradient_mid'] : '#eab308',
            'gradient_to' => filled($row['gradient_to'] ?? null) ? (string) $row['gradient_to'] : '#22c55e',
        ];
    }

    private static function normalizeUploadedPath(mixed $path): ?string
    {
        if (is_array($path)) {
            $path = $path[0] ?? null;
        }

        if (! is_string($path) || $path === '') {
            return null;
        }

        return $path;
    }

    private static function parseDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse((string) $value);
    }
}
