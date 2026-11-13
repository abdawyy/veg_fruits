<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Support\StoreCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class ProductSellingUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_kg_product_is_sold_only_by_kilogram(): void
    {
        $product = $this->product(['price_per_kg' => 40]);

        $this->assertSame(['kg'], $product->availableUnits());

        $this->get(route('store.shop'))
            ->assertOk()
            ->assertSee('value="kg"', false)
            ->assertDontSee('value="gram"', false);

        $this->get(route('store.product', $product))
            ->assertOk()
            ->assertSee('value="kg"', false)
            ->assertDontSee('value="gram"', false);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'gram',
            'quantity' => 250,
        ])->assertSessionHasErrors('unit');

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ])->assertRedirect();

        $this->assertSame('kg', StoreCart::lines()[0]['unit']);
    }

    public function test_piece_choice_on_a_kg_product_carries_the_piece_price(): void
    {
        $product = $this->product([
            'price_per_kg' => 40,
            'price_per_piece' => 6,
            'sell_by_piece' => true,
        ]);

        $this->get(route('store.product', $product))
            ->assertOk()
            ->assertSee('value="piece"', false)
            ->assertSee('data-qty-unit-label>kg', false)
            ->assertSee('"piece"', false);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'piece',
            'quantity' => 2,
        ])->assertRedirect();

        $this->assertSame('piece', StoreCart::lines()[0]['unit']);
        $this->assertSame(0, bccomp(StoreCart::subtotal(), '12', 4));
    }

    public function test_gram_product_charges_its_gram_price_and_deducts_stock(): void
    {
        Notification::fake();

        $product = $this->product([
            'unit' => 'gram',
            'price_per_kg' => 0,
            'price_per_gram' => 2,
            'track_stock' => true,
            'stock_quantity' => 500,
        ]);

        $this->assertSame(['gram'], $product->availableUnits());
        $this->assertSame('gram', $product->defaultOrderUnit());
        $this->assertNull(StoreCart::unitPriceForProduct($product, 'kg'));

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ])->assertSessionHasErrors('unit');

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'gram',
            'quantity' => 100,
        ])->assertRedirect();

        $city = City::query()->create([
            'code' => 'unit-city',
            'name' => ['en' => 'Test City', 'ar' => 'مدينة'],
            'shipping_fee' => '15.0000',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->get(route('store.cart'))->assertOk();

        $this->post(route('store.checkout.store'), [
            'checkout_nonce' => session('checkout_nonce'),
            'payment_method' => 'cod',
            'city_id' => $city->id,
            'shipping_address_line1' => '12 Nile Street',
            'customer_phone' => '01001234567',
            'customer_name' => 'Test Customer',
            'customer_email' => 'buyer@example.test',
        ])->assertRedirect(route('store.checkout.thanks'));

        $product->refresh();
        $this->assertSame(0, bccomp((string) $product->stock_quantity, '400', 4));

        $item = Order::query()->latest('id')->first()?->items()->first();
        $this->assertNotNull($item);
        $this->assertSame('gram', $item->unit);
        $this->assertSame(0, bccomp((string) $item->line_total, '200', 4));
    }

    public function test_piece_product_rejects_weight_units(): void
    {
        $product = $this->product([
            'unit' => 'piece',
            'price_per_piece' => 5,
        ]);

        $this->assertSame(['piece'], $product->fresh()->availableUnits());

        $this->get(route('store.product', $product))
            ->assertOk()
            ->assertSee('data-qty-unit-label>piece', false)
            ->assertSee('step="1"', false)
            ->assertDontSee('>kg<', false);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ])->assertSessionHasErrors('unit');

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'piece',
            'quantity' => 2,
        ])->assertRedirect();

        $this->assertSame(0, bccomp(StoreCart::subtotal(), '10', 4));
    }

    public function test_kg_product_rejects_a_gram_order(): void
    {
        $product = $this->product([
            'price_per_kg' => 20,
            'track_stock' => true,
            'stock_quantity' => 10,
        ]);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'gram',
            'quantity' => 500,
        ])->assertSessionHasErrors('unit');

        $this->assertSame([], StoreCart::lines());
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function product(array $overrides = []): Product
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce-'.uniqid(), 'ar' => 'produce-'.uniqid()],
            'is_active' => true,
        ]);

        return Product::query()->create(array_merge([
            'category_id' => $category->id,
            'name' => ['en' => 'Mint', 'ar' => 'نعناع'],
            'slug' => ['en' => 'mint-'.uniqid(), 'ar' => 'mint-'.uniqid()],
            'description' => ['en' => 'Fresh', 'ar' => 'طازج'],
            'sku' => 'UNIT-'.uniqid(),
            'price_per_kg' => 10,
            'is_active' => true,
        ], $overrides));
    }
}
