<?php

namespace App\Support;

use App\Models\ContentString;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

final class Cms
{
    /**
     * @return array<string, array{en: ?string, ar: ?string}>
     */
    private static function map(): array
    {
        if (! Schema::hasTable('content_strings')) {
            return [];
        }

        return Cache::rememberForever('content_strings_map', function (): array {
            $rows = ContentString::query()->get(['key', 'value_en', 'value_ar']);
            $out = [];
            foreach ($rows as $row) {
                $out[$row->key] = [
                    'en' => $row->value_en,
                    'ar' => $row->value_ar,
                ];
            }

            return $out;
        });
    }

    public static function text(string $key, string $fallback = '', ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $locale = in_array($locale, ['en', 'ar'], true) ? $locale : 'en';
        $map = self::map();
        $val = $map[$key][$locale] ?? null;

        return ($val !== null && trim($val) !== '') ? $val : $fallback;
    }
}
