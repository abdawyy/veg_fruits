<?php

namespace Tests\Feature;

use App\Actions\Subscriptions\ProcessDueSubscriptionsAction;
use App\Enums\SubscriptionInterval;
use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\ProduceBox;
use App\Models\ProduceBoxItem;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class PhaseDCommerceTest extends TestCase
{
    use RefreshDatabase;

    private function seedProduct(): Product
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Produce', 'ar' => 'خضار'],
            'slug' => ['en' => 'produce', 'ar' => 'produce'],
            'is_active' => true,
        ]);

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato', 'ar' => 'tomato'],
            'description' => ['en' => 'Fresh', 'ar' => 'طازج'],
            'sku' => 'TST-TOM-BOX',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);
    }

    private function seedBox(Product $product): ProduceBox
    {
        $box = ProduceBox::query()->create([
            'name' => ['en' => 'Starter box', 'ar' => 'صندوق البداية'],
            'slug' => ['en' => 'starter-box', 'ar' => 'starter-box'],
            'price' => '50.0000',
            'is_active' => true,
        ]);

        ProduceBoxItem::query()->create([
            'produce_box_id' => $box->id,
            'product_id' => $product->id,
            'quantity' => '1.0000',
            'unit' => 'kg',
        ]);

        return $box;
    }

    private function seedCity(): City
    {
        return City::query()->create([
            'code' => 'test-city-box',
            'name' => ['en' => 'Test City', 'ar' => 'مدينة'],
            'shipping_fee' => '5.0000',
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }

    public function test_box_can_be_added_to_cart(): void
    {
        $product = $this->seedProduct();
        $box = $this->seedBox($product);

        $this->post(route('store.boxes.cart', $box))
            ->assertRedirect(route('store.cart'))
            ->assertSessionHas('status');

        $this->get(route('store.cart'))
            ->assertOk()
            ->assertSee('Starter box', false);
    }

    public function test_subscription_signup_creates_order_and_subscription(): void
    {
        $product = $this->seedProduct();
        $box = $this->seedBox($product);
        $city = $this->seedCity();

        $user = User::factory()->create([
            'phone_number' => '01011112222',
        ]);

        $this->actingAs($user)
            ->post(route('store.boxes.subscribe', $box), [
                'interval' => SubscriptionInterval::Monthly->value,
                'city_id' => $city->id,
                'shipping_address_line1' => '12 Box St',
                'customer_phone' => '01011112222',
                'payment_method' => 'cod',
            ])
            ->assertRedirect(route('store.checkout.thanks'));

        $this->assertDatabaseCount('subscriptions', 1);
        $this->assertGreaterThan(0, Order::query()->where('user_id', $user->id)->count());
    }

    public function test_process_due_subscriptions_creates_renewal_order(): void
    {
        $product = $this->seedProduct();
        $box = $this->seedBox($product);
        $city = $this->seedCity();

        $user = User::factory()->create([
            'phone_number' => '01033334444',
            'default_city_id' => $city->id,
            'default_address_line1' => '9 Renewal Rd',
        ]);

        Subscription::query()->create([
            'user_id' => $user->id,
            'produce_box_id' => $box->id,
            'interval' => SubscriptionInterval::Monthly,
            'status' => 'active',
            'starts_at' => now()->subMonth(),
            'next_order_at' => now()->subDay(),
            'last_generated_at' => now()->subMonth(),
        ]);

        $ordersBefore = Order::query()->where('user_id', $user->id)->count();

        $processed = app(ProcessDueSubscriptionsAction::class)->execute(Carbon::now());

        $this->assertSame(1, $processed);
        $this->assertSame($ordersBefore + 1, Order::query()->where('user_id', $user->id)->count());
    }
}
