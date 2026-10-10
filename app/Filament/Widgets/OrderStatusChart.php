<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderStatusChart extends ChartWidget
{
    use HasDashboardPeriod;

    private const COLORS = [
        'pending' => '#f59e0b',
        'confirmed' => '#3b82f6',
        'processing' => '#8b5cf6',
        'shipped' => '#06b6d4',
        'delivered' => '#16a34a',
        'cancelled' => '#ef4444',
    ];

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Orders by status');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('track fulfilment and cancellations');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $period = $this->period();

        $counts = Order::query()
            ->whereBetween('created_at', [$period->start(), $period->end()])
            ->select(['status', DB::raw('COUNT(*) as orders_count')])
            ->groupBy('status')
            ->pluck('orders_count', 'status')
            ->mapWithKeys(fn ($count, $status): array => [
                ($status instanceof OrderStatus ? $status->value : (string) $status) => (int) $count,
            ]);

        $statuses = array_values(array_filter(
            OrderStatus::cases(),
            fn (OrderStatus $status): bool => ($counts[$status->value] ?? 0) > 0,
        ));

        return [
            'labels' => array_map(fn (OrderStatus $s): string => $s->label(), $statuses),
            'datasets' => [
                [
                    'label' => __('Orders'),
                    'data' => array_map(fn (OrderStatus $s): int => $counts[$s->value], $statuses),
                    'backgroundColor' => array_map(fn (OrderStatus $s): string => self::COLORS[$s->value] ?? '#94a3b8', $statuses),
                    'borderWidth' => 0,
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'cutout' => '62%',
            'plugins' => ['legend' => ['position' => 'right']],
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
        ];
    }
}
