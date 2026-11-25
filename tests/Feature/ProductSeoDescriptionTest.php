<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Support\ProduceSeoCopy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductSeoDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_page_publishes_the_seo_description_in_both_languages(): void
    {
        $copy = ProduceSeoCopy::forName('Tomato');
        $this->assertNotNull($copy);

        $category = Category::query()->create([
            'name' => ['en' => 'Vegetables', 'ar' => 'خضروات'],
            'slug' => ['en' => 'vegetables', 'ar' => 'vegetables'],
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato', 'ar' => 'tomato'],
            'description' => $copy,
            'sku' => 'VEG-SEO-153',
            'price_per_kg' => 20,
            'is_active' => true,
        ]);

        $this->get(route('store.product', $product))
            ->assertOk()
            ->assertSee('About Tomato', false)
            ->assertSee($copy['en'], false)
            ->assertSee('<meta name="description" content="Tomato is the heart of the Egyptian kitchen at AL-DAWY, sold by the kilo across Egypt."', false)
            ->assertSee('"@type":"Product"', false)
            ->assertDontSee('Tomato — AL-DAWY — AL-DAWY', false);

        $this->get(route('store.product', $product).'?locale=ar')
            ->assertOk()
            ->assertSee('عن طماطم', false)
            ->assertSee($copy['ar'], false)
            ->assertSee('<meta name="description" content="'.$copy['ar'].'"', false);
    }
}
