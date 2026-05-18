<?php

namespace App\Support;

use App\Models\Product;
use App\Models\SeoSetting;

final class StoreSeo
{
    public static function pageMeta(?string $routeName = null): array
    {
        $routeName ??= (string) request()->route()?->getName();
        $loc = app()->getLocale();
        $suffix = $loc === 'ar' ? '_ar' : '_en';
        $seo = SeoSetting::current();

        return match ($routeName) {
            'store.home' => [
                'title' => self::pick($seo, 'home_meta_title'.$suffix, config('app.name')),
                'description' => self::pick($seo, 'home_meta_description'.$suffix, ''),
            ],
            'store.shop' => [
                'title' => self::pick($seo, 'shop_meta_title'.$suffix, ''),
                'description' => self::pick($seo, 'shop_meta_description'.$suffix, ''),
            ],
            'store.services' => [
                'title' => self::pick($seo, 'services_meta_title'.$suffix, ''),
                'description' => self::pick($seo, 'services_meta_description'.$suffix, ''),
            ],
            'store.fruits' => [
                'title' => '',
                'description' => '',
            ],
            'store.vegetables' => [
                'title' => '',
                'description' => '',
            ],
            'store.product' => self::productMeta(request()->route('product'), $seo, $suffix),
            default => [
                'title' => '',
                'description' => '',
            ],
        };
    }

    private static function pick(SeoSetting $seo, string $attr, string $fallback): string
    {
        $v = $seo->{$attr} ?? null;

        return ($v !== null && trim((string) $v) !== '') ? (string) $v : $fallback;
    }

    /**
     * @return array{title: string, description: string}
     */
    private static function productMeta(?Product $product, SeoSetting $seo, string $suffix): array
    {
        if (! $product instanceof Product) {
            return ['title' => '', 'description' => ''];
        }
        $loc = $suffix === '_ar' ? 'ar' : 'en';
        $name = $product->getTranslation('name', $loc);
        $suffixText = self::pick($seo, 'product_meta_title_suffix'.$suffix, 'AL-DAWY');
        $title = trim($name.' — '.$suffixText);
        $desc = strip_tags((string) $product->getTranslation('description', $loc));

        return [
            'title' => $title,
            'description' => mb_substr($desc, 0, 300),
        ];
    }
}
