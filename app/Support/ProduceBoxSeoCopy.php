<?php

namespace App\Support;

use App\Models\ProduceBox;

final class ProduceBoxSeoCopy
{
    /**
     * @return array<string, array{description: array{en: string, ar: string}, meta_title: array{en: string, ar: string}, meta_description: array{en: string, ar: string}}>
     */
    public static function all(): array
    {
        /** @var array<string, array{description: array{en: string, ar: string}, meta_title: array{en: string, ar: string}, meta_description: array{en: string, ar: string}}> */
        return require database_path('seeders/Catalog/produce_box_seo.php');
    }

    /**
     * @return array{description: array{en: string, ar: string}, meta_title: array{en: string, ar: string}, meta_description: array{en: string, ar: string}}|null
     */
    public static function forSlug(string $englishSlug): ?array
    {
        return self::all()[$englishSlug] ?? null;
    }

    /** Write catalog SEO copy onto matching boxes. Price and contents stay as they are. */
    public static function applyToCatalogBoxes(): int
    {
        $updated = 0;

        foreach (self::all() as $slug => $copy) {
            $box = ProduceBox::query()->where('slug->en', $slug)->first();
            if (! $box instanceof ProduceBox) {
                continue;
            }

            $box->description = $copy['description'];
            $box->meta_title = $copy['meta_title'];
            $box->meta_description = $copy['meta_description'];
            $box->save();
            $updated++;
        }

        return $updated;
    }
}
