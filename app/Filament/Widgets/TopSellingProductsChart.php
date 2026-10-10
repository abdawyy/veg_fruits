<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopSellingProductsChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Best sellers');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('top products by revenue');
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

        $orderIds = Order::query()
            ->whereBetween('created_at', [$period->start(), $period->end()])
            ->where('status', '!=', OrderStatus::Cancelled)
            ->select('id');

        $rows = OrderItem::query()
            ->whereNotNull('product_id')
            ->whereIn('order_id', $orderIds)
            ->select(['product_id', DB::raw('SUM(line_total) as revenue'), DB::raw('COUNT(DISTINCT order_id) as orders_count')])
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get();

        $products = Product::query()->whereIn('id', $rows->pluck('product_id'))->get()->keyBy('id');
        $locale = app()->getLocale();

        return [
            'labels' => $rows->map(function (OrderItem $row) use ($products, $locale): string {
                $product = $products->get($row->product_id);
                $name = $product ? ($product->getTranslation('name', $locale) ?: $product->getTranslation('name', 'en')) : '#'.$row->product_id;

                return mb_strlen($name) > 24 ? mb_substr($name, 0, 22).'…' : $name;
            })->all(),
            'datasets' => [
                [
                    'label' => __('Revenue (EGP)'),
                    'data' => $rows->pluck('revenue')->map(fn ($v) => round((float) $v, 2))->all(),
                    'backgroundColor' => 'rgba(22, 163, 74, 0.55)',
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
                'x' => ['beginAtZero' => true],
                'y' => ['grid' => ['display' => false]],
            ],
        ];
    }
}
