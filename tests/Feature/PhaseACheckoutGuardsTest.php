<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PhaseACheckoutGuardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_add_inactive_product_to_cart(): void
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce', 'ar' => 'produce'],
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Inactive', 'ar' => 'غير متاح'],
            'slug' => ['en' => 'inactive', 'ar' => 'inactive'],
            'description' => ['en' => 'N/A', 'ar' => 'N/A'],
            'sku' => 'TST-INACTIVE',
            'price_per_kg' => 10,
            'is_active' => false,
        ]);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ])->assertSessionHasErrors('product_id');
    }

    public function test_checkout_rejects_when_cart_has_dropped_lines(): void
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
            'sku' => 'TST-TOM-DROP',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);

        $city = City::query()->create([
            'code' => 'test-city-drop',
            'name' => ['en' => 'Test City', 'ar' => 'مدينة'],
            'shipping_fee' => '5.0000',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->withSession([
            'aldawy_cart' => [
                ['product_id' => $product->id, 'kg' => '1.0000', 'preparation_service_ids' => [], 'packaging_type_id' => null],
            ],
            'checkout_nonce' => str_repeat('a', 40),
        ]);

        $product->update(['is_active' => false]);

        $this->post(route('store.checkout.store'), [
            'checkout_nonce' => str_repeat('a', 40),
            'payment_method' => 'cod',
            'city_id' => $city->id,
            'shipping_address_line1' => '1 Test St',
            'customer_phone' => '01000000000',
        ])->assertRedirect(route('store.cart'))
            ->assertSessionHasErrors('cart');
    }

    public function test_duplicate_checkout_nonce_is_rejected(): void
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
            'sku' => 'TST-TOM-DUP',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);

        $city = City::query()->create([
            'code' => 'test-city-dup',
            'name' => ['en' => 'Test City', 'ar' => 'مدينة'],
            'shipping_fee' => '5.0000',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $nonce = str_repeat('b', 40);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ]);

        $this->withSession(['checkout_nonce' => $nonce]);

        $payload = [
            'checkout_nonce' => $nonce,
            'payment_method' => 'cod',
            'city_id' => $city->id,
            'shipping_address_line1' => '2 Test St',
            'customer_phone' => '01000000001',
        ];

        $this->post(route('store.checkout.store'), $payload)
            ->assertRedirect(route('store.checkout.thanks'));

        $this->post(route('store.checkout.store'), $payload)
            ->assertRedirect(route('store.cart'))
            ->assertSessionHasErrors('cart');
    }
}
