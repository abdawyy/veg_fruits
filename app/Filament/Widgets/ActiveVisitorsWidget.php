<?php

namespace App\Filament\Widgets;

use App\Models\SitePageView;
use App\Models\SiteVisitor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class ActiveVisitorsWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected ?string $heading = 'Traffic';

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        if (! Schema::hasTable('site_visitors')) {
            return [
                Stat::make(__('aldawy.visitors'), '—'),
            ];
        }

        $stats = [
            Stat::make(__('aldawy.active_now_5min'), (string) SiteVisitor::activeSinceMinutes(5)),
            Stat::make(__('aldawy.sessions_today'), (string) SiteVisitor::query()->whereDate('last_seen_at', today())->count()),
            Stat::make(__('aldawy.total_pings'), (string) SiteVisitor::query()->count()),
        ];

        if (Schema::hasTable('site_page_views')) {
            $stats[] = Stat::make(__('aldawy.analytics_page_views_7d'), (string) SitePageView::query()
                ->where('visited_at', '>=', now()->subDays(7))
                ->count());
        }

        if (Schema::hasColumn('site_visitors', 'referrer')) {
            $stats[] = Stat::make(__('aldawy.analytics_sessions_referrer_7d'), (string) SiteVisitor::query()
                ->whereNotNull('referrer')
                ->where('referrer', '!=', '')
                ->where('first_seen_at', '>=', now()->subDays(7))
                ->count());
        }

        if (Schema::hasColumn('site_visitors', 'utm_campaign')) {
            $stats[] = Stat::make(__('aldawy.analytics_utm_sessions_7d'), (string) SiteVisitor::query()
                ->whereNotNull('utm_campaign')
                ->where('utm_campaign', '!=', '')
                ->where('first_seen_at', '>=', now()->subDays(7))
                ->count());
        }

        if (Schema::hasColumn('site_visitors', 'device_type')) {
            $stats[] = Stat::make(__('aldawy.analytics_mobile_today'), (string) SiteVisitor::query()
                ->whereDate('last_seen_at', today())
                ->where('device_type', 'mobile')
                ->count());
        }

        return $stats;
    }
}
