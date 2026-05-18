<?php

namespace App\Filament\Widgets;

use App\Models\SitePageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class TopStorePathsChart extends ChartWidget
{
    protected ?string $heading = 'Top storefront paths (14 days)';

    protected ?string $description = 'Counted when visitors move to a new page in the shop (GET).';

    protected int|string|array $columnSpan = 'full';

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
            return [
                'labels' => [],
                'datasets' => [
                    ['label' => __('Views'), 'data' => []],
                ],
            ];
        }

        $rows = SitePageView::query()
            ->selectRaw('path, count(*) as c')
            ->where('visited_at', '>=', now()->subDays(14))
            ->groupBy('path')
            ->orderByDesc('c')
            ->limit(12)
            ->get();

        $labels = $rows->pluck('path')->map(fn (string $p): string => strlen($p) > 36 ? substr($p, 0, 33).'…' : $p)->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Views'),
                    'data' => $rows->pluck('c')->map(fn ($n) => (int) $n)->all(),
                    'backgroundColor' => 'rgba(22, 163, 74, 0.55)',
                    'borderColor' => '#15803d',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}
