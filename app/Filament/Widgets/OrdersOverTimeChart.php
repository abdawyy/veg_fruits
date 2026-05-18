<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersOverTimeChart extends ChartWidget
{
    protected ?string $heading = 'Orders (last 14 days)';

    protected ?string $description = 'Quick trend — full figures on the Dashboard stats cards.';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $labels = [];
        $values = [];

        for ($i = 13; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('M j');
            $values[] = Order::query()->whereDate('created_at', $day)->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Orders'),
                    'data' => $values,
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
        ];
    }
}
