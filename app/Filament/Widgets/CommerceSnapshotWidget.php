<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class CommerceSnapshotWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '60s';

    protected ?string $heading = 'Commerce snapshot (7 days)';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $since = now()->subDays(7);

        $orders = Order::query()->where('created_at', '>=', $since)->count();

        $sessions = 0;
        if (Schema::hasTable('site_visitors')) {
            $sessions = SiteVisitor::query()->where('first_seen_at', '>=', $since)->count();
        }

        $pageViews = 0;
        if (Schema::hasTable('site_page_views')) {
            $pageViews = SitePageView::query()->where('visited_at', '>=', $since)->count();
        }

        $cartViews = 0;
        if (Schema::hasTable('site_page_views')) {
            $cartViews = SitePageView::query()
                ->where('visited_at', '>=', $since)
                ->where('path', 'like', '%cart%')
                ->count();
        }

        $rate = $sessions > 0 ? round(($orders / $sessions) * 100, 1) : 0.0;

        return [
            Stat::make(__('aldawy.analytics_orders_7d'), (string) $orders),
            Stat::make(__('aldawy.analytics_new_sessions_7d'), (string) $sessions),
            Stat::make(__('aldawy.analytics_page_views_7d'), (string) $pageViews),
            Stat::make(__('aldawy.analytics_cart_views_7d'), (string) $cartViews),
            Stat::make(__('aldawy.analytics_order_rate_7d'), $rate.'%'),
        ];
    }
}
