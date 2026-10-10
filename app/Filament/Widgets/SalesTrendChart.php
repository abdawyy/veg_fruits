<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\Order;
use App\Support\Analytics\TimeBuckets;
use Filament\Widgets\ChartWidget;

class SalesTrendChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '320px';

    public function getHeading(): string
    {
        return __('Revenue & orders trend');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('cancelled orders excluded').' · '.TimeBuckets::timezone();
    }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $period = $this->period();
        $series = TimeBuckets::series(
            Order::query()->where('status', '!=', OrderStatus::Cancelled),
            'created_at',
            $period->start(),
            $period->end(),
            $period->granularity(),
            'total',
        );

        return [
            'labels' => array_column($series, 'label'),
            'datasets' => [
                [
                    'label' => __('Revenue (EGP)'),
                    'data' => array_map(fn (array $b): float => round($b['sum'], 2), array_values($series)),
                    'backgroundColor' => 'rgba(22, 163, 74, 0.35)',
                    'borderColor' => '#16a34a',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                    'yAxisID' => 'y',
                    'order' => 2,
                ],
                [
                    'type' => 'line',
                    'label' => __('Orders'),
                    'data' => array_column($series, 'count'),
                    'borderColor' => '#0f766e',
                    'backgroundColor' => '#0f766e',
                    'pointRadius' => 2,
                    'tension' => 0.35,
                    'yAxisID' => 'y1',
                    'order' => 1,
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
            'interaction' => ['mode' => 'index', 'intersect' => false],
            'plugins' => ['legend' => ['position' => 'bottom']],
            'scales' => [
                'x' => ['grid' => ['display' => false]],
                'y' => ['beginAtZero' => true, 'position' => 'left', 'title' => ['display' => true, 'text' => 'EGP']],
                'y1' => ['beginAtZero' => true, 'position' => 'right', 'grid' => ['drawOnChartArea' => false], 'ticks' => ['precision' => 0]],
            ],
        ];
    }
}
