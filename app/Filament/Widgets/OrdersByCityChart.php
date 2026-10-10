<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\City;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersByCityChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Orders by delivery area');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('cancelled orders excluded');
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

        $rows = Order::query()
            ->whereBetween('created_at', [$period->start(), $period->end()])
            ->where('status', '!=', OrderStatus::Cancelled)
            ->select(['city_id', DB::raw('COUNT(*) as orders_count'), DB::raw('SUM(total) as revenue')])
            ->groupBy('city_id')
            ->orderByDesc('orders_count')
            ->limit(10)
            ->get();

        $cities = City::query()->whereIn('id', $rows->pluck('city_id')->filter())->get()->keyBy('id');
        $locale = app()->getLocale();

        $labels = $rows->map(function ($row) use ($cities, $locale): string {
            $city = $row->city_id ? $cities->get($row->city_id) : null;

            return $city ? ($city->getTranslation('name', $locale) ?: $city->code) : __('No city');
        })->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Orders'),
                    'data' => $rows->pluck('orders_count')->map(fn ($v) => (int) $v)->all(),
                    'backgroundColor' => 'rgba(22, 163, 74, 0.6)',
                    'borderColor' => '#15803d',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                    'xAxisID' => 'x',
                ],
                [
                    'label' => __('Revenue (EGP)'),
                    'data' => $rows->pluck('revenue')->map(fn ($v) => round((float) $v, 2))->all(),
                    'backgroundColor' => 'rgba(168, 85, 247, 0.45)',
                    'borderColor' => '#7e22ce',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                    'xAxisID' => 'x1',
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
            'interaction' => ['mode' => 'index', 'intersect' => false],
            'plugins' => ['legend' => ['position' => 'bottom']],
            'scales' => [
                'x' => ['beginAtZero' => true, 'position' => 'bottom', 'ticks' => ['precision' => 0]],
                'x1' => ['beginAtZero' => true, 'position' => 'top', 'grid' => ['drawOnChartArea' => false]],
                'y' => ['grid' => ['display' => false]],
            ],
        ];
    }
}
