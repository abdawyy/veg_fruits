<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Widgets\Concerns\BuildsKpiStats;
use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\Order;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Support\Analytics\TimeBuckets;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class TrafficKpiStats extends StatsOverviewWidget
{
    use BuildsKpiStats;
    use HasDashboardPeriod;

    protected ?string $pollingInterval = '30s';

    protected int|string|array $columnSpan = 'full';

    protected int|array|null $columns = ['default' => 1, 'md' => 2, 'xl' => 5];

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        if (! Schema::hasTable('site_visitors')) {
            return [Stat::make(__('Visitors'), '—')];
        }

        $period = $this->period();
        $start = $period->start();
        $end = $period->end();
        $previousStart = $period->previousStart();
        $previousEnd = $period->previousEnd();

        $sessions = $this->sessionsBetween($start, $end);
        $previousSessions = $this->sessionsBetween($previousStart, $previousEnd);

        $sessionSeries = TimeBuckets::series(SiteVisitor::query(), 'first_seen_at', $start, $end, $period->granularity());

        $pageViews = 0;
        $previousPageViews = 0;
        $pageViewSpark = null;
        if (Schema::hasTable('site_page_views')) {
            $pageViews = SitePageView::query()->whereBetween('visited_at', [$start, $end])->count();
            $previousPageViews = SitePageView::query()->where('visited_at', '>=', $previousStart)->where('visited_at', '<', $previousEnd)->count();
            $pageViewSpark = array_column(TimeBuckets::series(SitePageView::query(), 'visited_at', $start, $end, $period->granularity()), 'count');
        }

        $orders = $this->ordersBetween($start, $end);
        $previousOrders = $this->ordersBetween($previousStart, $previousEnd);
        $conversion = $sessions > 0 ? $orders / $sessions * 100 : 0.0;
        $previousConversion = $previousSessions > 0 ? $previousOrders / $previousSessions * 100 : 0.0;

        $mobile = Schema::hasColumn('site_visitors', 'device_type')
            ? SiteVisitor::query()->whereBetween('first_seen_at', [$start, $end])->where('device_type', 'mobile')->count()
            : 0;
        $mobileShare = $sessions > 0 ? $mobile / $sessions * 100 : 0.0;

        return [
            Stat::make(__('Visitors right now'), number_format(SiteVisitor::activeSinceMinutes(5)))
                ->description(__('Active in the last 5 minutes'))
                ->descriptionIcon('heroicon-m-signal')
                ->icon(Heroicon::OutlinedSignal)
                ->color('success'),
            $this->kpi(__('Sessions'), number_format($sessions), $sessions, $previousSessions, array_column($sessionSeries, 'count'), Heroicon::OutlinedUsers),
            $this->kpi(__('Page views'), number_format($pageViews), $pageViews, $previousPageViews, $pageViewSpark, Heroicon::OutlinedEye),
            $this->kpi(__('Conversion rate'), number_format($conversion, 1).'%', round($conversion, 2), round($previousConversion, 2), null, Heroicon::OutlinedCursorArrowRays),
            Stat::make(__('Mobile visitors'), number_format($mobileShare, 0).'%')
                ->description(trans_choice(':count mobile session|:count mobile sessions', $mobile))
                ->icon(Heroicon::OutlinedDevicePhoneMobile)
                ->color('gray'),
        ];
    }

    private function sessionsBetween(Carbon $from, Carbon $to): int
    {
        return SiteVisitor::query()->where('first_seen_at', '>=', $from)->where('first_seen_at', '<', $to)->count();
    }

    private function ordersBetween(Carbon $from, Carbon $to): int
    {
        return Order::query()
            ->where('created_at', '>=', $from)
            ->where('created_at', '<', $to)
            ->where('status', '!=', OrderStatus::Cancelled)
            ->count();
    }
}
