<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class MinimumOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        config(['aldawy.min_order_subtotal' => '50']);
    }

    public function test_cart_below_the_minimum_cannot_check_out(): void
    {
        [$product, $city] = $this->catalog();

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ])->assertRedirect();

        $this->get(route('store.cart'))
            ->assertOk()
            ->assertSee('Add 40.00 EGP more')
            ->assertSee('id="aldawy-checkout-submit"', false)
            ->assertSee('disabled:opacity-60" disabled', false);

        $this->post(route('store.checkout.store'), $this->checkoutPayload($city->id))
            ->assertRedirect(route('store.cart'))
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_cart_that_meets_the_minimum_can_check_out(): void
    {
        [$product, $city] = $this->catalog();

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 5,
        ])->assertRedirect();

        $this->get(route('store.cart'))->assertOk()->assertDontSee('Add 40.00 EGP more');

        $this->post(route('store.checkout.store'), $this->checkoutPayload($city->id))
            ->assertRedirect(route('store.checkout.thanks'));

        $this->assertDatabaseCount('orders', 1);
    }

    public function test_zero_minimum_allows_a_small_cart(): void
    {
        config(['aldawy.min_order_subtotal' => '0']);
        [$product, $city] = $this->catalog();

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ])->assertRedirect();

        $this->post(route('store.checkout.store'), $this->checkoutPayload($city->id))
            ->assertRedirect(route('store.checkout.thanks'));
    }

    public function test_order_api_rejects_a_basket_under_the_minimum(): void
    {
        [$product, $city] = $this->catalog();

        $this->postJson('/api/v1/orders', [
            'city_id' => $city->id,
            'shipping_address_line1' => '12 Nile Street',
            'customer_phone' => '01001234567',
            'payment_method' => 'cod',
            'lines' => [[
                'product_id' => $product->id,
                'unit' => 'kg',
                'quantity' => 1,
            ]],
        ])->assertStatus(422)->assertJsonFragment(['message' => 'Add 40.00 EGP more. Minimum order is 50.00 EGP of products. Delivery is extra.']);
    }

    /**
     * @return array{0: Product, 1: City}
     */
    private function catalog(): array
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce-min', 'ar' => 'produce-min'],
            'is_active' => true,
        ]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato-min', 'ar' => 'tomato-min'],
            'sku' => 'MIN-TOM',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);
        $city = City::query()->create([
            'code' => 'min-city',
            'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'],
            'shipping_fee' => '25.0000',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return [$product, $city];
    }

    /**
     * @return array<string, mixed>
     */
    private function checkoutPayload(int $cityId): array
    {
        $this->get(route('store.cart'))->assertOk();

        return [
            'checkout_nonce' => session('checkout_nonce'),
            'payment_method' => 'cod',
            'city_id' => $cityId,
            'shipping_address_line1' => '12 Nile Street',
            'customer_phone' => '01001234567',
        ];
    }
}
