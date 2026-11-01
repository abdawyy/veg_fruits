<?php

namespace Tests\Feature;

use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Cities\Pages\ListCities;
use App\Filament\Resources\ContentStrings\Pages\ListContentStrings;
use App\Filament\Resources\HomeBanners\Pages\ListHomeBanners;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\ProduceBoxes\Pages\ListProduceBoxes;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Category;
use App\Models\City;
use App\Models\ContentString;
use App\Models\HomeBanner;
use App\Models\Order;
use App\Models\ProduceBox;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class AdminTableSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_product_table_search_matches_translated_names_and_sku(): void
    {
        $category = Category::query()->create([
            'name' => ['en' => 'Fruits', 'ar' => 'فواكه'],
            'slug' => ['en' => 'fruits', 'ar' => 'fruits'],
            'is_active' => true,
        ]);

        $tomato = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato', 'ar' => 'tomato'],
            'sku' => 'SKU-TOMATO',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);
        $cucumber = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Cucumber', 'ar' => 'خيار'],
            'slug' => ['en' => 'cucumber', 'ar' => 'cucumber'],
            'sku' => 'SKU-CUCUMBER',
            'price_per_kg' => 8,
            'is_active' => true,
        ]);

        $admin = $this->admin('Product');

        Livewire::actingAs($admin)
            ->test(ListProducts::class)
            ->assertCanSeeTableRecords([$tomato, $cucumber])
            ->searchTable('tomato')
            ->assertCanSeeTableRecords([$tomato])
            ->assertCanNotSeeTableRecords([$cucumber])
            ->searchTable('طماطم')
            ->assertCanSeeTableRecords([$tomato])
            ->assertCanNotSeeTableRecords([$cucumber])
            ->searchTable('sku-cucumber')
            ->assertCanSeeTableRecords([$cucumber])
            ->assertCanNotSeeTableRecords([$tomato])
            ->searchTable('fruits')
            ->assertCanSeeTableRecords([$tomato, $cucumber]);
    }

    public function test_category_city_and_box_tables_search_translated_names(): void
    {
        $fruits = Category::query()->create([
            'name' => ['en' => 'Fruits', 'ar' => 'فواكه'],
            'slug' => ['en' => 'fruits', 'ar' => 'fruits'],
            'is_active' => true,
        ]);
        $greens = Category::query()->create([
            'name' => ['en' => 'Greens', 'ar' => 'ورقيات'],
            'slug' => ['en' => 'greens', 'ar' => 'greens'],
            'is_active' => true,
        ]);

        $cairo = City::query()->create([
            'code' => 'zone-1',
            'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'],
            'shipping_fee' => 25,
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $giza = City::query()->create([
            'code' => 'zone-2',
            'name' => ['en' => 'Giza', 'ar' => 'الجيزة'],
            'shipping_fee' => 30,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $weekly = ProduceBox::query()->create([
            'name' => ['en' => 'Weekly Box', 'ar' => 'صندوق أسبوعي'],
            'slug' => ['en' => 'weekly-box', 'ar' => 'weekly-box'],
            'price' => 100,
            'is_active' => true,
        ]);
        $family = ProduceBox::query()->create([
            'name' => ['en' => 'Family Box', 'ar' => 'صندوق عائلي'],
            'slug' => ['en' => 'family-box', 'ar' => 'family-box'],
            'price' => 180,
            'is_active' => true,
        ]);

        $admin = $this->admin('Category', 'City', 'ProduceBox');

        Livewire::actingAs($admin)
            ->test(ListCategories::class)
            ->assertSeeHtml('tableSearch')
            ->searchTable('فواكه')
            ->assertCanSeeTableRecords([$fruits])
            ->assertCanNotSeeTableRecords([$greens]);

        Livewire::actingAs($admin)
            ->test(ListCities::class)
            ->searchTable('cairo')
            ->assertCanSeeTableRecords([$cairo])
            ->assertCanNotSeeTableRecords([$giza]);

        Livewire::actingAs($admin)
            ->test(ListProduceBoxes::class)
            ->searchTable('أسبوعي')
            ->assertCanSeeTableRecords([$weekly])
            ->assertCanNotSeeTableRecords([$family]);
    }

    public function test_order_content_and_banner_tables_search_visible_text(): void
    {
        $city = City::query()->create([
            'code' => 'zone-1',
            'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'],
            'shipping_fee' => 25,
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $category = Category::query()->create([
            'name' => ['en' => 'Fruits', 'ar' => 'فواكه'],
            'slug' => ['en' => 'fruits', 'ar' => 'fruits'],
            'is_active' => true,
        ]);
        $mango = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Mango', 'ar' => 'مانجو'],
            'slug' => ['en' => 'mango', 'ar' => 'mango'],
            'sku' => 'SKU-MANGO',
            'price_per_kg' => 20,
            'is_active' => true,
        ]);

        $matching = Order::query()->create([
            'reference' => 'AL-NOUR-1',
            'customer_phone' => '01011112222',
            'customer_name' => 'Nour Hassan',
            'customer_email' => 'nour@example.com',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'subtotal' => 20,
            'packaging_fee' => 0,
            'total' => 20,
            'city_id' => $city->id,
        ]);
        $matching->items()->create([
            'product_id' => $mango->id,
            'product_name_snapshot' => ['en' => 'Mango', 'ar' => 'مانجو'],
            'unit' => 'kg',
            'quantity' => 1,
            'packaging' => 'none',
            'unit_price' => 20,
            'line_total' => 20,
        ]);
        $other = Order::query()->create([
            'reference' => 'AL-OTHER-2',
            'customer_phone' => '01099998888',
            'customer_name' => 'Omar Adel',
            'customer_email' => 'omar@example.com',
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'subtotal' => 5,
            'packaging_fee' => 0,
            'total' => 5,
        ]);

        $copy = ContentString::query()->create([
            'key' => 'hero_title',
            'value_en' => 'Fresh produce today',
            'value_ar' => 'منتجات طازجة',
            'group' => 'home',
        ]);
        $otherCopy = ContentString::query()->create([
            'key' => 'footer_note',
            'value_en' => 'Delivery hours',
            'value_ar' => 'مواعيد التوصيل',
            'group' => 'footer',
        ]);

        $banner = HomeBanner::query()->create([
            'sort_order' => 1,
            'title' => ['en' => 'Mango week', 'ar' => 'أسبوع المانجو'],
            'subtitle' => ['en' => 'Seasonal', 'ar' => 'موسمي'],
            'is_active' => true,
        ]);
        $otherBanner = HomeBanner::query()->create([
            'sort_order' => 2,
            'title' => ['en' => 'Greens', 'ar' => 'ورقيات'],
            'subtitle' => ['en' => 'Daily', 'ar' => 'يومي'],
            'is_active' => true,
        ]);

        $admin = $this->admin('Order', 'ContentString', 'HomeBanner');

        Livewire::actingAs($admin)
            ->test(ListOrders::class)
            ->searchTable('nour')
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other])
            ->searchTable('مانجو')
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other])
            ->searchTable('cairo')
            ->assertCanSeeTableRecords([$matching])
            ->assertCanNotSeeTableRecords([$other]);

        Livewire::actingAs($admin)
            ->test(ListContentStrings::class)
            ->searchTable('fresh produce')
            ->assertCanSeeTableRecords([$copy])
            ->assertCanNotSeeTableRecords([$otherCopy]);

        Livewire::actingAs($admin)
            ->test(ListHomeBanners::class)
            ->searchTable('المانجو')
            ->assertCanSeeTableRecords([$banner])
            ->assertCanNotSeeTableRecords([$otherBanner]);
    }

    private function admin(string ...$subjects): User
    {
        $role = Role::findOrCreate('super_admin', 'web');

        foreach ($subjects as $subject) {
            $role->givePermissionTo(Permission::findOrCreate("ViewAny:{$subject}", 'web'));
        }

        $admin = User::factory()->admin()->create();
        $admin->assignRole($role);

        return $admin;
    }
}
