<?php

namespace App\Support;

use App\Models\HomeBanner;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
            'cta_buttons' => self::ctaButtons($row),
            'cta_label' => [
                'en' => (string) data_get($row, 'cta_label.en', ''),
                'ar' => (string) data_get($row, 'cta_label.ar', ''),
            ],
            'cta_url' => filled($row['cta_url'] ?? null) ? (string) $row['cta_url'] : null,
            'image_url' => filled($row['image_url'] ?? null) ? (string) $row['image_url'] : null,
            'image_path' => self::normalizeUploadedPath($row['image_path'] ?? null),
            'hot_product_skus' => self::hotProductSkus($row['hot_product_skus'] ?? null),
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
            'cta_buttons' => self::ctaButtonsFromImport($row),
            'cta_label' => [
                'en' => trim((string) ($row['cta_label_en'] ?? '')),
                'ar' => trim((string) ($row['cta_label_ar'] ?? '')),
            ],
            'cta_url' => filled($row['cta_url'] ?? null) ? (string) $row['cta_url'] : null,
            'image_url' => filled($row['image_url'] ?? null) ? (string) $row['image_url'] : null,
            'hot_product_skus' => self::hotProductSkus($row['hot_product_skus'] ?? null),
            'gradient_from' => filled($row['gradient_from'] ?? null) ? (string) $row['gradient_from'] : '#f97316',
            'gradient_mid' => filled($row['gradient_mid'] ?? null) ? (string) $row['gradient_mid'] : '#eab308',
            'gradient_to' => filled($row['gradient_to'] ?? null) ? (string) $row['gradient_to'] : '#22c55e',
        ];
    }

    private static function normalizeUploadedPath(mixed $path): ?string
    {
        if (is_array($path)) {
            $path = collect($path)->first(fn (mixed $file): bool => filled($file));
        }

        if ($path instanceof TemporaryUploadedFile || $path instanceof UploadedFile) {
            return self::storeUploadedFile($path);
        }

        if (! is_string($path) || $path === '') {
            return null;
        }

        $path = str_replace('\\', '/', $path);

        if (str_starts_with($path, 'livewire-file:')) {
            return self::storeUploadedFile(
                TemporaryUploadedFile::createFromLivewire(substr($path, strlen('livewire-file:'))),
            );
        }

        if (str_starts_with($path, 'livewire-tmp/')) {
            return self::promoteTemporaryPath($path);
        }

        return $path;
    }

    private static function storeUploadedFile(TemporaryUploadedFile|UploadedFile $file): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $extension = preg_replace('/[^a-z0-9]+/', '', $extension) ?: 'jpg';
        $target = 'home-banners/'.Str::ulid().'.'.$extension;

        $stream = $file instanceof TemporaryUploadedFile ? $file->readStream() : fopen($file->getRealPath(), 'r');
        if ($stream === false || $stream === null) {
            return null;
        }

        $stored = Storage::disk('public')->put($target, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        return $stored ? $target : null;
    }

    private static function promoteTemporaryPath(string $path): ?string
    {
        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'jpg');
        $extension = preg_replace('/[^a-z0-9]+/', '', $extension) ?: 'jpg';
        $target = 'home-banners/'.Str::ulid().'.'.$extension;
        $stream = $disk->readStream($path);
        if ($stream === null) {
            return null;
        }

        $stored = $disk->put($target, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        return $stored ? $target : null;
    }

    private static function hotProductSkus(mixed $value): ?string
    {
        if (is_array($value)) {
            $skus = array_values(array_filter(array_map(
                fn ($sku) => trim((string) $sku),
                $value,
            )));

            return $skus === [] ? null : implode(',', $skus);
        }

        $skus = trim((string) $value);

        return $skus === '' ? null : $skus;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return list<array{label: array{en: string, ar: string}, url: ?string, product_ids: list<int>, product_id: ?int}>
     */
    private static function ctaButtons(array $row): array
    {
        $productIds = is_array($row['cta_product_ids'] ?? null) ? $row['cta_product_ids'] : [];

        return HomeBanner::packCtaButtons(
            [
                'en' => (string) data_get($row, 'cta_label.en', ''),
                'ar' => (string) data_get($row, 'cta_label.ar', ''),
            ],
            $row['cta_url'] ?? null,
            $productIds,
        );
    }

    /**
     * @param  array<string, mixed>  $row
     * @return list<array{label: array{en: string, ar: string}, url: ?string, product_ids: list<int>, product_id: ?int}>
     */
    private static function ctaButtonsFromImport(array $row): array
    {
        return HomeBanner::packCtaButtons(
            [
                'en' => trim((string) ($row['cta_label_en'] ?? '')),
                'ar' => trim((string) ($row['cta_label_ar'] ?? '')),
            ],
            $row['cta_url'] ?? null,
            [],
        );
    }

    private static function parseDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse((string) $value);
    }
}
