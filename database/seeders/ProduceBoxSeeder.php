<?php

namespace Database\Seeders;

use App\Models\ProduceBox;
use App\Models\ProduceBoxItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProduceBoxSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->limit(3)
            ->get();

        if ($products->isEmpty()) {
            return;
        }

        $box = ProduceBox::query()->updateOrCreate(
            ['slug->en' => 'weekly-essentials'],
            [
                'name' => ['en' => 'Weekly essentials box', 'ar' => 'صندوق الأسبوع الأساسي'],
                'slug' => ['en' => 'weekly-essentials', 'ar' => 'weekly-essentials'],
                'price' => '199.0000',
                'is_active' => true,
            ],
        );

        ProduceBoxItem::query()->where('produce_box_id', $box->id)->delete();

        foreach ($products as $index => $product) {
            ProduceBoxItem::query()->create([
                'produce_box_id' => $box->id,
                'product_id' => $product->id,
                'quantity' => $index === 0 ? '1.0000' : '0.5000',
                'unit' => 'kg',
            ]);
        }
    }
}
