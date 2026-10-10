<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\ProductView;
use App\Models\SitePageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

/** Visits, product views and orders bucketed by a time pattern (hour of day, weekday). */
abstract class ActivityPatternChart extends ChartWidget
{
    public ?string $filter = '30';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '320px';

    /** @return array<int, int> */
    abstract protected function bucket(Builder $query, string $column): array;

    /** @return list<string> */
    abstract protected function labels(): array;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => __('Last 7 days'),
            '30' => __('Last 30 days'),
            '90' => __('Last 90 days'),
        ];
    }

    protected function periodStart(): Carbon
    {
        $days = in_array($this->filter, ['7', '30', '90'], true) ? (int) $this->filter : 30;

        return now()->subDays($days);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $since = $this->periodStart();

        $pageViews = Schema::hasTable('site_page_views')
            ? $this->bucket(SitePageView::query()->where('visited_at', '>=', $since), 'visited_at')
            : [];

        $productViews = Schema::hasTable('product_views')
            ? $this->bucket(ProductView::query()->where('visited_at', '>=', $since), 'visited_at')
            : [];

        $orders = $this->bucket(
            Order::query()
                ->where('created_at', '>=', $since)
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
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => __('Product views'),
                    'data' => array_values($productViews),
                    'backgroundColor' => 'rgba(234, 179, 8, 0.5)',
                    'borderColor' => '#a16207',
                    'borderWidth' => 1,
                    'yAxisID' => 'y',
                ],
                [
                    'type' => 'line',
                    'label' => __('Orders'),
                    'data' => array_values($orders),
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.15)',
                    'tension' => 0.35,
                    'yAxisID' => 'y1',
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
            'scales' => [
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
