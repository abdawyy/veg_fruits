<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\Product;
use App\Models\ProductView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TopProductsByViewsChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Most viewed products');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('product page opens');
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
        if (! Schema::hasTable('product_views')) {
            return ['labels' => [], 'datasets' => [['label' => __('Views'), 'data' => []]]];
        }

        $period = $this->period();

        $rows = ProductView::query()
            ->whereBetween('visited_at', [$period->start(), $period->end()])
            ->select(['product_id', DB::raw('COUNT(*) as views')])
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->limit(8)
            ->get();

        $products = Product::query()->whereIn('id', $rows->pluck('product_id'))->get(['id', 'name'])->keyBy('id');
        $locale = app()->getLocale();

        $labels = $rows->map(function ($row) use ($products, $locale): string {
            $product = $products->get($row->product_id);
            $name = $product
                ? ($product->getTranslation('name', $locale) ?: $product->getTranslation('name', 'en'))
                : null;
            $name = $name ?: '#'.$row->product_id;

            return mb_strlen($name) > 28 ? mb_substr($name, 0, 25).'…' : $name;
        })->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Views'),
                    'data' => $rows->pluck('views')->map(fn ($v) => (int) $v)->all(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.6)',
                    'borderColor' => '#1d4ed8',
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
