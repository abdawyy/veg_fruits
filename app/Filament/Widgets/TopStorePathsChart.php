<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\SitePageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class TopStorePathsChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Top shop pages');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('page views per path');
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
        if (! Schema::hasTable('site_page_views')) {
            return ['labels' => [], 'datasets' => [['label' => __('Views'), 'data' => []]]];
        }

        $period = $this->period();

        $rows = SitePageView::query()
            ->selectRaw('path, count(*) as c')
            ->whereBetween('visited_at', [$period->start(), $period->end()])
            ->groupBy('path')
            ->orderByDesc('c')
            ->limit(10)
            ->get();

        $labels = $rows->pluck('path')->map(fn (string $p): string => mb_strlen($p) > 36 ? mb_substr($p, 0, 33).'…' : $p)->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Views'),
                    'data' => $rows->pluck('c')->map(fn ($n) => (int) $n)->all(),
                    'backgroundColor' => 'rgba(22, 163, 74, 0.6)',
                    'borderColor' => '#15803d',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
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
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'x' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
                'y' => ['grid' => ['display' => false]],
            ],
        ];
    }
}
