<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\Order;
use App\Models\ProductView;
use App\Models\SitePageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

/** Visits, product views and orders bucketed by a time pattern (hour of day, weekday). */
abstract class ActivityPatternChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    /** @return array<int, int> */
    abstract protected function bucket(Builder $query, string $column): array;

    /** @return list<string> */
    abstract protected function labels(): array;

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
        $start = $period->start();
        $end = $period->end();

        $pageViews = Schema::hasTable('site_page_views')
            ? $this->bucket(SitePageView::query()->whereBetween('visited_at', [$start, $end]), 'visited_at')
            : [];

        $productViews = Schema::hasTable('product_views')
            ? $this->bucket(ProductView::query()->whereBetween('visited_at', [$start, $end]), 'visited_at')
            : [];

        $orders = $this->bucket(
            Order::query()
                ->whereBetween('created_at', [$start, $end])
                ->where('status', '!=', OrderStatus::Cancelled),
            'created_at',
        );

        return [
            'labels' => $this->labels(),
            'datasets' => [
                [
                    'label' => __('Page views'),
                    'data' => array_values($pageViews),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.45)',
                    'borderColor' => '#2563eb',
                    'borderWidth' => 1,
                    'borderRadius' => 3,
                    'yAxisID' => 'y',
                    'order' => 3,
                ],
                [
                    'label' => __('Product views'),
                    'data' => array_values($productViews),
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                    'borderColor' => '#d97706',
                    'borderWidth' => 1,
                    'borderRadius' => 3,
                    'yAxisID' => 'y',
                    'order' => 2,
                ],
                [
                    'type' => 'line',
                    'label' => __('Orders'),
                    'data' => array_values($orders),
                    'borderColor' => '#16a34a',
                    'backgroundColor' => '#16a34a',
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
                'y' => [
                    'beginAtZero' => true,
                    'position' => 'left',
                    'title' => ['display' => true, 'text' => __('Views')],
                ],
                'y1' => [
                    'beginAtZero' => true,
                    'position' => 'right',
                    'grid' => ['drawOnChartArea' => false],
                    'ticks' => ['precision' => 0],
                    'title' => ['display' => true, 'text' => __('Orders')],
                ],
            ],
        ];
    }
}
