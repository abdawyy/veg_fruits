<?php

namespace App\Support;

use App\Models\Product;

final class ProduceSeoCopy
{
    /**
     * @return array<string, array{en: string, ar: string}>
     */
    public static function all(): array
    {
        /** @var array<string, array{en: string, ar: string}> $fruits */
        $fruits = require database_path('seeders/Catalog/produce_seo_fruits.php');
        /** @var array<string, array{en: string, ar: string}> $vegetables */
        $vegetables = require database_path('seeders/Catalog/produce_seo_vegetables.php');

        return $fruits + $vegetables;
    }

    /**
     * @return array{en: string, ar: string}|null
     */
    public static function forName(string $englishName): ?array
    {
        return self::all()[$englishName] ?? null;
    }

    /** Write catalog SEO copy onto matching products. Prices and stock are left alone. */
    public static function applyToCatalogProducts(): int
    {
        $copy = self::all();
        $updated = 0;

        Product::query()->orderBy('id')->each(function (Product $product) use ($copy, &$updated): void {
            $name = $product->getTranslation('name', 'en');
            if (! isset($copy[$name])) {
                return;
            }

            $product->description = $copy[$name];
            $product->save();
            $updated++;
        });

        return $updated;
    }
}
