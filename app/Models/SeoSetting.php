<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SeoSetting extends Model
{
    protected $fillable = [
        'home_meta_title_en',
        'home_meta_title_ar',
        'home_meta_description_en',
        'home_meta_description_ar',
        'shop_meta_title_en',
        'shop_meta_title_ar',
        'shop_meta_description_en',
        'shop_meta_description_ar',
        'services_meta_title_en',
        'services_meta_title_ar',
        'services_meta_description_en',
        'services_meta_description_ar',
        'product_meta_title_suffix_en',
        'product_meta_title_suffix_ar',
        'og_image_url',
    ];

    /**
     * Do not cache the model instance: serialized Eloquent models in cache can
     * become __PHP_Incomplete_Class after deploys or autoload changes.
     */
    public static function current(): self
    {
        if (! Schema::hasTable('seo_settings')) {
            return new self;
        }

        return self::query()->firstOrCreate([], []);
    }

    protected static function booted(): void
    {
        // Clear legacy cache key if present (older versions cached the model).
        static::saved(function (): void {
            Cache::forget('seo_settings_singleton');
        });

        static::deleted(function (): void {
            Cache::forget('seo_settings_singleton');
        });
    }
}
