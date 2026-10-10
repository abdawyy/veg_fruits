<?php

namespace Tests\Feature;

use App\Filament\Widgets\ActivityByHourChart;
use App\Filament\Widgets\ActivityByWeekdayChart;
use App\Filament\Widgets\LatestOrdersTable;
use App\Filament\Widgets\OrdersByCityChart;
use App\Filament\Widgets\OrderStatusChart;
use App\Filament\Widgets\SalesKpiStats;
use App\Filament\Widgets\SalesTrendChart;
use App\Filament\Widgets\TopProductsByViewsChart;
use App\Filament\Widgets\TopSellingProductsChart;
use App\Filament\Widgets\TopStorePathsChart;
use App\Filament\Widgets\TrafficKpiStats;
use App\Filament\Widgets\TrafficReferrersChart;
use App\Filament\Widgets\VisitorLocationsChart;
use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Models\User;
use App\Support\Analytics\DashboardPeriod;
use App\Support\Analytics\TimeBuckets;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_period_today_starts_at_cairo_midnight_and_compares_with_the_previous_window(): void
    {
        config(['analytics.timezone' => 'Africa/Cairo']);
        Carbon::setTestNow('2026-01-15 10:00:00');

        $today = DashboardPeriod::from('today');
        $this->assertSame('2026-01-14 22:00:00', $today->start()->format('Y-m-d H:i:s'));
        $this->assertSame('2026-01-13 22:00:00', $today->previousStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2026-01-14 10:00:00', $today->previousEnd()->format('Y-m-d H:i:s'));
        $this->assertSame('hour', $today->granularity());

        $this->assertSame('day', DashboardPeriod::from('30')->granularity());
        $this->assertSame('month', DashboardPeriod::from('365')->granularity());
        $this->assertSame(DashboardPeriod::DEFAULT, DashboardPeriod::from('bogus')->key);
    }

    public function test_time_series_is_continuous_and_sums_in_cairo_days(): void
    {
        config(['analytics.timezone' => 'Africa/Cairo']);
        $user = User::factory()->create();

        // 23:30 UTC on Jan 10 is already Jan 11 in Cairo
        $this->order($user, '2026-01-10 23:30:00', '100');
        $this->order($user, '2026-01-12 09:00:00', '40');

        $series = TimeBuckets::series(
            Order::query(),
            'created_at',
            Carbon::parse('2026-01-10 00:00:00'),
            Carbon::parse('2026-01-13 00:00:00'),
            'day',
            'total',
        );

        $this->assertSame(['2026-01-10', '2026-01-11', '2026-01-12', '2026-01-13'], array_keys($series));
        $this->assertSame([0, 1, 1, 0], array_column($series, 'count'));
        $this->assertEqualsWithDelta(100.0, $series['2026-01-11']['sum'], 0.001);
        $this->assertSame('Jan 11', $series['2026-01-11']['label']);
    }

    public function test_sales_kpis_compare_against_the_previous_period(): void
    {
        $user = User::factory()->create();
        $this->order($user, now()->subDays(2)->toDateTimeString(), '300');
        $this->order($user, now()->subDays(3)->toDateTimeString(), '100', 'pending');
        $this->order($user, now()->subDays(4)->toDateTimeString(), '999', 'cancelled');
        $this->order($user, now()->subDays(10)->toDateTimeString(), '200');

        $this->actingAs($this->admin());

        Livewire::test(SalesKpiStats::class, ['pageFilters' => ['period' => '7']])
            ->assertOk()
            ->assertSee('EGP 400.00')
            ->assertSee('EGP 200.00')
            ->assertSee('+100.0% vs previous period');
    }

    public function test_every_dashboard_widget_renders_for_every_period(): void
    {
        $this->seedActivity();
        $this->actingAs($this->admin());

        $widgets = [
            SalesKpiStats::class, SalesTrendChart::class, OrderStatusChart::class, OrdersByCityChart::class,
            TopSellingProductsChart::class, TopProductsByViewsChart::class, LatestOrdersTable::class,
            TrafficKpiStats::class, ActivityByHourChart::class, ActivityByWeekdayChart::class,
            VisitorLocationsChart::class, TrafficReferrersChart::class, TopStorePathsChart::class,
        ];

        foreach (array_keys(DashboardPeriod::options()) as $period) {
            foreach ($widgets as $widget) {
                Livewire::test($widget, ['pageFilters' => ['period' => (string) $period]])->assertOk();
            }
        }

        Livewire::test(LatestOrdersTable::class)->assertSee('AL-DASH-1');
    }

    public function test_both_dashboard_pages_load(): void
    {
        $this->seedActivity();
        $this->actingAs($this->admin());

        $this->get('/admin')->assertOk()->assertSee('Sales overview');
        $this->get('/admin/traffic')->assertOk()->assertSee('Customers &amp; traffic', false);
    }

    private function seedActivity(): void
    {
        $city = City::query()->create(['code' => 'cairo', 'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'], 'shipping_fee' => 25, 'is_active' => true, 'sort_order' => 0]);
        $category = Category::query()->create(['name' => ['en' => 'Produce', 'ar' => 'خضار'], 'slug' => ['en' => 'produce', 'ar' => 'produce'], 'is_active' => true]);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => ['en' => 'Tomato', 'ar' => 'طماطم'],
            'slug' => ['en' => 'tomato', 'ar' => 'tomato'],
            'sku' => 'TST-DASH',
            'price_per_kg' => 10,
            'is_active' => true,
        ]);

        $order = $this->order(User::factory()->create(), now()->subHour()->toDateTimeString(), '50', 'delivered', 'AL-DASH-1');
        $order->forceFill(['city_id' => $city->id])->save();
        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name_snapshot' => 'Tomato',
            'unit' => 'kg',
            'packaging' => 'bag',
            'quantity' => 5,
            'unit_price' => 10,
            'line_total' => 50,
        ]);

        SiteVisitor::query()->create([
            'session_id' => 's-dash', 'device_type' => 'mobile', 'referrer' => 'https://www.facebook.com/ad',
            'country' => 'Egypt', 'country_code' => 'EG', 'city' => 'Cairo',
            'geo_resolved_at' => now(), 'first_seen_at' => now()->subMinutes(2), 'last_seen_at' => now(),
        ]);
        SitePageView::query()->create(['session_id' => 's-dash', 'path' => '/products/tomato', 'visited_at' => now()->subMinute()]);
        ProductView::query()->create(['product_id' => $product->id, 'session_id' => 's-dash', 'visited_at' => now()->subMinute()]);
    }

    private function admin(): User
    {
        $admin = User::factory()->admin()->create();
        $admin->assignRole(Role::findOrCreate('super_admin', 'web'));

        return $admin;
    }

    private function order(User $user, string $at, string $total, string $status = 'delivered', ?string $reference = null): Order
    {
        $order = Order::query()->create([
            'reference' => $reference ?? 'AL-T-'.uniqid(),
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
