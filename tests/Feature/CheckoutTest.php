<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\User;
use App\Listeners\OnOrderCreatedGenerateInvoiceAndNotify;
use App\Notifications\AdminNewOrderNotification;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_checkout_and_notifications_fire(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'email' => 'admin-test@example.test',
            'is_admin' => true,
        ]);

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
            'sku' => 'TST-TOM-CHK',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);

        $city = City::query()->create([
            'code' => 'test-city-checkout',
            'name' => ['en' => 'Test City', 'ar' => 'مدينة اختبار'],
            'shipping_fee' => '15.0000',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->post(route('store.cart.add'), [
            'product_id' => $product->id,
            'unit' => 'kg',
            'quantity' => 1,
        ])->assertRedirect();

        $this->get(route('store.cart'))->assertOk();
        $nonce = session('checkout_nonce');
        $this->assertIsString($nonce);
        $this->assertSame(40, strlen($nonce));

        $this->post(route('store.checkout.store'), [
            'checkout_nonce' => $nonce,
            'payment_method' => 'cod',
            'city_id' => $city->id,
            'shipping_address_line1' => '12 Nile Street',
            'shipping_address_line2' => 'Floor 3',
            'customer_phone' => '01001234567',
            'customer_name' => 'Test Customer',
            'customer_email' => 'buyer@example.test',
            'notes' => 'Ring the bell',
        ])->assertRedirect(route('store.checkout.thanks'));

        $this->followRedirects()->assertOk();

        $order = \App\Models\Order::query()->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertSame((string) $city->id, (string) $order->city_id);
        $this->assertSame('12 Nile Street', $order->shipping_address_line1);
        $this->assertSame('Floor 3', $order->shipping_address_line2);
        $this->assertEqualsWithDelta(15.0, (float) $order->shipping_fee, 0.0001);
        $this->assertEqualsWithDelta(25.0, (float) $order->total, 0.0001);

        Notification::assertSentOnDemand(OrderConfirmationNotification::class);
        Notification::assertSentTo($admin, AdminNewOrderNotification::class);

        $this->assertContains(
            ShouldQueue::class,
            class_implements(OnOrderCreatedGenerateInvoiceAndNotify::class),
        );
    }
}
