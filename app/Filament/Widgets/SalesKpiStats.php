<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Widgets\Concerns\BuildsKpiStats;
use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\Order;
use App\Support\Analytics\TimeBuckets;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class SalesKpiStats extends StatsOverviewWidget
{
    use BuildsKpiStats;
    use HasDashboardPeriod;

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected int|array|null $columns = ['default' => 1, 'md' => 2, 'xl' => 4];

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $period = $this->period();
        $start = $period->start();
        $end = $period->end();
        $current = $this->paidOrders()->whereBetween('created_at', [$start, $end]);
        $previous = $this->paidOrders()->where('created_at', '>=', $period->previousStart())->where('created_at', '<', $period->previousEnd());

        $revenue = (float) (clone $current)->sum('total');
        $orders = (clone $current)->count();
        $previousRevenue = (float) (clone $previous)->sum('total');
        $previousOrders = (clone $previous)->count();

        $average = $orders > 0 ? $revenue / $orders : 0.0;
        $previousAverage = $previousOrders > 0 ? $previousRevenue / $previousOrders : 0.0;

        $series = TimeBuckets::series($this->paidOrders(), 'created_at', $start, $end, $period->granularity(), 'total');
        $revenueSpark = array_map(fn (array $b): float => round($b['sum'], 2), array_values($series));
        $ordersSpark = array_map(fn (array $b): int => $b['count'], array_values($series));

        $pending = Order::query()->where('status', OrderStatus::Pending)->count();

        return [
            $this->kpi(__('Revenue'), self::money($revenue), $revenue, $previousRevenue, $revenueSpark, Heroicon::OutlinedBanknotes),
            $this->kpi(__('Orders'), number_format($orders), $orders, $previousOrders, $ordersSpark, Heroicon::OutlinedShoppingBag),
            $this->kpi(__('Average order value'), self::money($average), $average, $previousAverage, null, Heroicon::OutlinedReceiptPercent),
            Stat::make(__('Pending orders'), number_format($pending))
                ->description($pending > 0 ? __('Waiting for confirmation — open orders') : __('All caught up'))
                ->descriptionIcon($pending > 0 ? 'heroicon-m-arrow-right' : 'heroicon-m-check-circle')
                ->icon(Heroicon::OutlinedClock)
                ->color($pending > 0 ? 'warning' : 'success')
                ->url(OrderResource::getUrl('index')),
        ];
    }

    private function paidOrders(): Builder
    {
        return Order::query()->where('status', '!=', OrderStatus::Cancelled);
    }

    private static function money(float $amount): string
    {
        return 'EGP '.number_format($amount, 2);
    }
}
