<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SalesOverviewStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Analytics';

    protected ?string $description = 'Revenue and order counts (admin dashboard).';

    protected function getStats(): array
    {
        $orderQuery = Order::query()->where('status', '!=', OrderStatus::Cancelled);

        $revenue = (string) (clone $orderQuery)->sum('total');
        $orders = (clone $orderQuery)->count();
        $recent = Order::query()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return [
            Stat::make('Revenue (excl. cancelled)', Number::format((float) $revenue, precision: 2))
                ->description('All-time non-cancelled orders'),
            Stat::make('Active orders', Number::format($orders))
                ->description('Non-cancelled records'),
            Stat::make('New (7 days)', Number::format($recent))
                ->description('Order volume'),
        ];
    }
}
