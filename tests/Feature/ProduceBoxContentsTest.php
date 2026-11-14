<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ProduceBox;
use App\Models\ProduceBoxItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProduceBoxContentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_box_page_lists_selected_products_with_sale_price_and_seo(): void
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce-box', 'ar' => 'produce-box'],
            'is_active' => true,
        ]);

        $tomato = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato-box', 'ar' => 'tomato-box'],
            'sku' => 'BOX-TOM',
            'price_per_kg' => 20,
            'is_active' => true,
        ]);
        $cucumber = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Cucumber', 'ar' => 'خيار'],
            'slug' => ['en' => 'cucumber-box', 'ar' => 'cucumber-box'],
            'sku' => 'BOX-CUC',
            'price_per_kg' => 12,
            'is_active' => false,
        ]);

        $box = ProduceBox::query()->create([
            'name' => ['en' => 'Salad box', 'ar' => 'صندوق سلطة'],
            'slug' => ['en' => 'salad-box', 'ar' => 'salad-box'],
            'description' => ['en' => 'A fresh salad mix.', 'ar' => 'سلطة طازجة.'],
            'meta_title' => ['en' => 'Salad box SEO', 'ar' => 'صندوق سلطة'],
            'meta_description' => ['en' => 'Tomato and cucumber by the kilo.', 'ar' => 'طماطم وخيار بالكيلو.'],
            'price' => 80,
            'compare_at_price' => 120,
            'is_active' => true,
        ]);

        ProduceBoxItem::query()->create([
            'produce_box_id' => $box->id,
            'product_id' => $tomato->id,
            'quantity' => 5,
            'unit' => 'kg',
        ]);
        ProduceBoxItem::query()->create([
            'produce_box_id' => $box->id,
            'product_id' => $cucumber->id,
            'quantity' => 3,
            'unit' => 'kg',
        ]);

        $this->assertSame('Tomato 5 kg, Cucumber 3 kg', $box->contentsLabel('en'));

        $this->get(route('store.boxes.show', $box))
            ->assertOk()
            ->assertSee('Salad box SEO — AL-DAWY', false)
            ->assertSee('Tomato and cucumber by the kilo.', false)
            ->assertSee('A fresh salad mix.')
            ->assertSee('Tomato 5 kg, Cucumber 3 kg')
            ->assertSee('120.00')
            ->assertSee('80.00');
    }
}
