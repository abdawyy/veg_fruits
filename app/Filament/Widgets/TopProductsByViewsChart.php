<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class TopProductsByViewsChart extends ChartWidget
{
    protected ?string $heading = 'Most viewed products (catalog)';

    protected ?string $description = 'Based on product detail page loads (view counter).';

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
        $rows = Product::query()
            ->orderByDesc('view_count')
            ->limit(10)
            ->get(['id', 'view_count', 'name']);

        if ($rows->isEmpty()) {
            return [
                'labels' => [],
                'datasets' => [
                    ['label' => __('Views'), 'data' => []],
                ],
            ];
        }

        $labels = $rows->map(function (Product $p): string {
            $n = $p->getTranslation('name', app()->getLocale()) ?: $p->getTranslation('name', 'en') ?: '#'.$p->id;

            return strlen($n) > 28 ? substr($n, 0, 25).'…' : $n;
        })->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Views'),
                    'data' => $rows->pluck('view_count')->map(fn ($v) => (int) $v)->all(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}
