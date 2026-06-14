<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProduceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array{fruits: list<array>, vegetables: list<array>} $catalog */
        $catalog = require database_path('seeders/Catalog/produce_items.php');

        $fruits = Category::query()->updateOrCreate(
            ['slug->en' => 'fruits'],
            [
                'name' => ['en' => 'Fruits', 'ar' => 'فواكه'],
                'slug' => ['en' => 'fruits', 'ar' => 'فواكه'],
                'is_active' => true,
            ],
        );

        $vegetables = Category::query()->updateOrCreate(
            ['slug->en' => 'vegetables'],
            [
                'name' => ['en' => 'Vegetables', 'ar' => 'خضروات'],
                'slug' => ['en' => 'vegetables', 'ar' => 'خضروات'],
                'is_active' => true,
            ],
        );

        $this->seedGroup($fruits->id, 'FRU', $catalog['fruits']);
        $this->seedGroup($vegetables->id, 'VEG', $catalog['vegetables']);
    }

    /**
     * @param  list<array{0: string, 1: string, 2: string, 3: float, 4: ?float, 5: bool}>  $rows
     */
    private function seedGroup(int $categoryId, string $skuPrefix, array $rows): void
    {
        foreach ($rows as $row) {
            [$en, $ar, $suffix, $priceKg, $pricePiece, $byPiece] = $row;
            $sku = $skuPrefix.'-'.$suffix;
            $slugBase = Str::slug($en);

            Product::query()->updateOrCreate(
                ['sku' => $sku],
                [
                    'category_id' => $categoryId,
                    'name' => ['en' => $en, 'ar' => $ar],
                    'slug' => ['en' => $slugBase, 'ar' => $slugBase],
                    'description' => [
                        'en' => 'Premium fresh '.$en.' — hand-selected for quality.',
                        'ar' => 'طازج ومختار بعناية — '.$ar,
                    ],
                    'image_url' => null,
                    'price_per_kg' => $priceKg,
                    'price_per_piece' => $pricePiece,
                    'sell_by_piece' => $byPiece,
                    'is_active' => true,
                ],
            );
        }
    }
}
