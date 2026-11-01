<?php

namespace Tests\Feature;

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\RelationManagers\OrderItemsRelationManager;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class OrderItemProductLabelTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_line_items_show_translated_product_names(): void
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
            'sku' => 'TST-LINE',
            'price_per_kg' => 52,
            'is_active' => true,
        ]);
        $order = Order::query()->create([
            'reference' => 'AL-LINE-1',
            'user_id' => User::factory()->create()->id,
            'customer_phone' => '01000000009',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'subtotal' => '208',
            'packaging_fee' => '0',
            'discount_amount' => '0',
            'shipping_fee' => '0',
            'total' => '208',
        ]);
        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name_snapshot' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'unit' => 'kg',
            'packaging' => '',
            'quantity' => '4',
            'unit_price' => '52',
            'line_total' => '208',
        ]);

        Livewire::test(OrderItemsRelationManager::class, [
            'ownerRecord' => $order,
            'pageClass' => EditOrder::class,
        ])
            ->loadTable()
            ->assertSee('Tomato')
            ->assertSee('طماطم')
            ->assertDontSee('—, —');
    }

    public function test_product_label_falls_back_to_the_linked_product(): void
    {
        $item = new OrderItem([
            'product_name_snapshot' => ['en' => '', 'ar' => ''],
        ]);
        $item->setRelation('product', new Product([
            'name' => ['en' => 'Cucumber', 'ar' => 'خيار'],
        ]));

        $this->assertSame('Cucumber — خيار', $item->productLabel());
    }
}
