<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CartAjaxAddTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_to_cart_json_returns_line_count(): void
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce', 'ar' => 'produce'],
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato', 'ar' => 'tomato'],
            'description' => ['en' => 'Fresh', 'ar' => 'طازج'],
            'sku' => 'TST-TOM-1',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);

        $response = $this->postJson(route('store.cart.add'), [
            'product_id' => $product->id,
            'kg' => 2.5,
        ]);

        $response->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('cart_line_count', 1);
    }
}
