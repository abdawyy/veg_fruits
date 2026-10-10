<?php

namespace Tests\Feature;

use App\Filament\Resources\Users\Widgets\CustomerActivityChart;
use App\Filament\Widgets\ActivityByHourChart;
use App\Filament\Widgets\ActivityByWeekdayChart;
use App\Filament\Widgets\OrdersByCityChart;
use App\Filament\Widgets\VisitorLocationsChart;
use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Models\User;
use App\Services\Analytics\VisitorGeoLocator;
use App\Support\Analytics\CustomerInsights;
use App\Support\Analytics\TimeBuckets;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class CustomerAnalyticsAndProductImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_image_is_an_absolute_url_and_wins_over_external_url(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/mango.jpg', UploadedFile::fake()->image('mango.jpg')->getContent());

        $product = $this->product([
            'image_url' => 'https://images.example.com/stock-mango.jpg',
            'image_path' => 'products/mango.jpg',
        ]);

        $url = $product->display_image_url;

        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL), 'Admin ImageColumn needs a full URL');
        $this->assertStringEndsWith('/storage/products/mango.jpg', $url);

        $product->image_path = null;
        $this->assertSame('https://images.example.com/stock-mango.jpg', $product->display_image_url);
    }

    public function test_admin_product_list_and_edit_page_show_the_uploaded_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/tomato.jpg', UploadedFile::fake()->image('tomato.jpg')->getContent());
        $product = $this->product(['image_path' => 'products/tomato.jpg']);

        $this->actingAs($this->admin(['ViewAny:Product', 'View:Product', 'Update:Product']));

        $this->get('/admin/products')->assertOk()->assertSee('/storage/products/tomato.jpg', false);
        $this->get('/admin/products/'.$product->id.'/edit')->assertOk()->assertSee('/storage/products/tomato.jpg', false);
    }

    public function test_time_buckets_report_in_cairo_time(): void
    {
        config(['analytics.timezone' => 'Africa/Cairo']);

        // Thu 2026-01-15 18:30 UTC = 20:30 Cairo; 23:30 UTC = Fri 01:30 Cairo
        $this->pageView('s-a', '2026-01-15 18:30:00');
        $this->pageView('s-a', '2026-01-15 23:30:00');

        $byHour = TimeBuckets::countByHour(SitePageView::query(), 'visited_at');
        $this->assertSame(1, $byHour[20]);
        $this->assertSame(1, $byHour[1]);
        $this->assertSame(2, array_sum($byHour));

        $byWeekday = TimeBuckets::countByWeekday(SitePageView::query(), 'visited_at');
        $this->assertSame(1, $byWeekday[Carbon::THURSDAY]);
        $this->assertSame(1, $byWeekday[Carbon::FRIDAY]);
    }

    public function test_geo_locator_parses_lookup_and_skips_private_ips(): void
    {
        config(['analytics.geoip.enabled' => true, 'analytics.geoip.url' => 'https://ipwho.is/{ip}']);
        Http::fake([
            'ipwho.is/*' => Http::response([
                'success' => true,
                'country' => 'Egypt',
                'country_code' => 'EG',
                'region' => 'Alexandria',
                'city' => 'Alexandria',
            ]),
        ]);

        $locator = app(VisitorGeoLocator::class);

        $this->assertSame(
            ['country_code' => 'EG', 'country' => 'Egypt', 'region' => 'Alexandria', 'city' => 'Alexandria'],
            $locator->locate('41.33.10.20'),
        );
        $this->assertSame(VisitorGeoLocator::LOCAL_NETWORK, $locator->locate('192.168.1.5')['country']);
        Http::assertSentCount(1);
    }

    public function test_visitor_location_is_stored_from_cloudflare_headers(): void
    {
        $this->withHeaders(['CF-IPCountry' => 'EG', 'cf-ipcity' => 'Cairo', 'cf-region' => 'Cairo Governorate'])
            ->get('/')
            ->assertOk();

        $visitor = SiteVisitor::query()->sole();
        $this->assertSame('EG', $visitor->country_code);
        $this->assertSame('Cairo', $visitor->city);
        $this->assertNotNull($visitor->geo_resolved_at);
    }

    public function test_visitor_location_is_looked_up_after_the_response(): void
    {
        Http::fake([
            '*' => Http::response(['success' => true, 'country' => 'Egypt', 'country_code' => 'EG', 'region' => 'Giza', 'city' => 'Giza']),
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '41.33.10.21'])->get('/')->assertOk();

        $visitor = SiteVisitor::query()->sole();
        $this->assertSame('Giza', $visitor->city);
        $this->assertSame('Giza, Egypt', $visitor->location_label);
    }

    public function test_customer_insights_and_admin_customer_page(): void
    {
        config(['analytics.timezone' => 'Africa/Cairo']);

        $customer = User::factory()->create(['name' => 'Insight Customer']);
        $tomato = $this->product();

        SiteVisitor::query()->create([
            'session_id' => 's-customer',
            'user_id' => $customer->id,
            'device_type' => 'mobile',
            'country_code' => 'EG',
            'country' => 'Egypt',
            'city' => 'Cairo',
            'geo_resolved_at' => now(),
            'first_seen_at' => '2026-01-15 17:00:00',
            'last_seen_at' => '2026-01-15 18:40:00',
        ]);
        $this->pageView('s-customer', '2026-01-15 18:10:00');
        foreach (['2026-01-15 18:20:00', '2026-01-15 18:25:00'] as $at) {
            ProductView::query()->create(['product_id' => $tomato->id, 'session_id' => 's-customer', 'user_id' => $customer->id, 'visited_at' => $at]);
        }
        $this->order($customer, '2026-01-15 18:35:00', '120');
        $this->order($customer, '2026-01-22 18:05:00', '80');
        $this->order($customer, '2026-01-23 10:00:00', '999', 'cancelled');

        $insights = CustomerInsights::for($customer);

        $this->assertSame(['Cairo, Egypt (1 session)'], $insights->locations());
        $this->assertSame(['Tomato — 2 views (last 2026-01-15 20:25)'], array_map(fn ($s) => $s, $insights->mostViewedProducts()));
        $summary = $insights->purchaseSummary();
        $this->assertSame(2, $summary['orders']);
        $this->assertSame('200.00 EGP', $summary['spent']);
        $this->assertSame(1, $summary['cancelled']);
        $this->assertStringContainsString('20:00–21:00', (string) $insights->usualPurchaseTime());
        $this->assertStringContainsString('Thursday', (string) $insights->usualPurchaseTime());

        $this->actingAs($this->admin(['ViewAny:User', 'View:User']));

        $this->get('/admin/users/'.$customer->id)
            ->assertOk()
            ->assertSee('Cairo, Egypt')
            ->assertSee('Tomato')
            ->assertSee('200.00 EGP');

        $this->get('/admin/users')->assertOk()->assertSee('Insight Customer');

        Livewire::test(CustomerActivityChart::class, ['record' => $customer])->assertOk();
    }

    public function test_dashboard_analytics_widgets_render(): void
    {
        $city = City::query()->create(['code' => 'cairo', 'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'], 'shipping_fee' => 25, 'is_active' => true, 'sort_order' => 0]);
        $order = $this->order(User::factory()->create(), now()->subDay()->toDateTimeString(), '50');
        $order->forceFill(['city_id' => $city->id])->save();
        SiteVisitor::query()->create([
            'session_id' => 's-dash', 'country' => 'Egypt', 'country_code' => 'EG', 'city' => 'Cairo',
            'geo_resolved_at' => now(), 'first_seen_at' => now(), 'last_seen_at' => now(),
        ]);

        $this->actingAs($this->admin([]));

        Livewire::test(ActivityByHourChart::class)->assertOk();
        Livewire::test(ActivityByWeekdayChart::class)->assertOk();
        Livewire::test(VisitorLocationsChart::class)->assertOk()->set('filter', 'country')->assertOk();
        Livewire::test(OrdersByCityChart::class)->assertOk();
        $this->get('/admin')->assertOk();
    }

    /**
     * @param  list<string>  $permissions
     */
    private function admin(array $permissions): User
    {
        $role = Role::findOrCreate('super_admin', 'web');
        foreach ($permissions as $name) {
            $role->givePermissionTo(Permission::findOrCreate($name, 'web'));
        }

        $admin = User::factory()->admin()->create();
        $admin->assignRole($role);

        return $admin;
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function product(array $overrides = []): Product
    {
        $category = Category::query()->firstOrCreate(
            ['id' => 1],
            ['name' => ['en' => 'Produce', 'ar' => 'خضار'], 'slug' => ['en' => 'produce', 'ar' => 'produce'], 'is_active' => true],
        );

        return Product::query()->create(array_merge([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato', 'ar' => 'tomato'],
            'sku' => 'TST-'.uniqid(),
            'price_per_kg' => 10,
            'is_active' => true,
        ], $overrides));
    }

    private function pageView(string $session, string $at): void
    {
        SitePageView::query()->create(['session_id' => $session, 'path' => '/', 'visited_at' => $at]);
    }

    private function order(User $user, string $at, string $total, string $status = 'delivered'): Order
    {
        $order = Order::query()->create([
            'reference' => 'AL-T-'.uniqid(),
            'user_id' => $user->id,
            'customer_phone' => '01000000009',
            'customer_email' => $user->email,
            'status' => $status,
            'payment_gateway' => 'cod',
            'subtotal' => $total,
            'packaging_fee' => '0',
            'discount_amount' => '0',
            'shipping_fee' => '0',
            'total' => $total,
        ]);
        $order->forceFill(['created_at' => $at])->save();

        return $order;
    }
}
