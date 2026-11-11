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

final class ProductOriginPriceTest extends TestCase
{
    use RefreshDatabase;

    public function test_local_only_product_rejects_imported_and_keeps_its_own_price(): void
    {
        $product = $this->product([
            'is_local' => true,
            'is_imported' => false,
            'price_per_kg' => 10,
            'imported_price_per_kg' => 40,
        ]);

        $this->assertSame(['local'], $product->availableOrigins());
        $this->assertNull(StoreCart::unitPriceForProduct($product, 'kg', 'imported'));

        $this->get(route('store.product', $product))
            ->assertOk()
            ->assertSee('value="local"', false)
            ->assertDontSee('value="imported"', false);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'origin' => 'imported',
            'quantity' => 1,
        ])->assertSessionHasErrors('origin');

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'origin' => 'local',
            'quantity' => 2,
        ])->assertRedirect();

        $this->assertSame(0, bccomp(StoreCart::subtotal(), '20', 4));
        $this->assertSame('local', StoreCart::lines()[0]['origin']);
    }

    public function test_imported_only_product_does_not_charge_the_local_price(): void
    {
        $product = $this->product([
            'is_local' => false,
            'is_imported' => true,
            'price_per_kg' => 99,
            'imported_price_per_kg' => 4,
        ]);

        $this->assertSame(['imported'], $product->fresh()->availableOrigins());
        $this->assertSame('imported', $product->defaultOrigin());
        $this->assertSame(0, bccomp(StoreCart::unitPriceForProduct($product, 'kg'), '4', 4));
        $this->assertNull(StoreCart::unitPriceForProduct($product, 'kg', 'local'));

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'origin' => 'local',
            'quantity' => 1,
        ])->assertSessionHasErrors('origin');

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'origin' => 'imported',
            'quantity' => 2,
        ])->assertRedirect();

        $this->assertSame(0, bccomp(StoreCart::subtotal(), '8', 4));
        $this->assertSame('imported', StoreCart::lines()[0]['origin']);
    }

    public function test_both_sources_keep_separate_prices_through_checkout(): void
    {
        Notification::fake();

        $product = $this->product([
            'is_local' => true,
            'is_imported' => true,
            'price_per_kg' => 10,
            'imported_price_per_kg' => 25,
        ]);

        $this->get(route('store.product', $product))
            ->assertOk()
            ->assertSee('value="local"', false)
            ->assertSee('value="imported"', false)
            ->assertSee('10.00')
            ->assertSee('25.00');

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'origin' => 'local',
            'quantity' => 1,
        ])->assertRedirect();

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'origin' => 'imported',
            'quantity' => 1,
        ])->assertRedirect();

        $this->assertCount(2, StoreCart::lines());
        $this->assertSame(0, bccomp(StoreCart::subtotal(), '35', 4));

        $city = City::query()->create([
            'code' => 'origin-city',
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

        $items = Order::query()->latest('id')->first()?->items()->orderBy('id')->get();
        $this->assertNotNull($items);
        $this->assertCount(2, $items);
        $this->assertSame('local', $items[0]->origin);
        $this->assertSame(0, bccomp((string) $items[0]->unit_price, '10', 4));
        $this->assertSame(0, bccomp((string) $items[0]->line_total, '10', 4));
        $this->assertSame('imported', $items[1]->origin);
        $this->assertSame(0, bccomp((string) $items[1]->unit_price, '25', 4));
        $this->assertSame(0, bccomp((string) $items[1]->line_total, '25', 4));
    }

    public function test_imported_source_without_a_price_cannot_use_the_local_price(): void
    {
        $product = $this->product([
            'is_local' => true,
            'is_imported' => true,
            'price_per_kg' => 10,
            'imported_price_per_kg' => null,
        ]);

        $this->assertNull(StoreCart::unitPriceForProduct($product, 'kg', 'imported'));

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'origin' => 'imported',
            'quantity' => 1,
        ])->assertSessionHasErrors('origin');

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'origin' => 'local',
            'quantity' => 1,
        ])->assertRedirect();

        $this->assertSame(0, bccomp(StoreCart::subtotal(), '10', 4));
    }

    public function test_a_product_with_neither_source_is_saved_as_local(): void
    {
        $product = $this->product([
            'is_local' => false,
            'is_imported' => false,
            'price_per_kg' => 7,
        ]);

        $this->assertTrue($product->fresh()->offersLocal());
        $this->assertFalse($product->fresh()->offersImported());
        $this->assertSame('local', $product->fresh()->defaultOrigin());
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
            'name' => ['en' => 'Mango', 'ar' => 'مانجو'],
            'slug' => ['en' => 'mango-'.uniqid(), 'ar' => 'mango-'.uniqid()],
            'description' => ['en' => 'Fresh', 'ar' => 'طازج'],
            'sku' => 'ORIGIN-'.uniqid(),
            'price_per_kg' => 10,
            'is_active' => true,
        ], $overrides));
    }
}
