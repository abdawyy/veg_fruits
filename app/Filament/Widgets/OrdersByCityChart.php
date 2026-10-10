<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\City;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersByCityChart extends ChartWidget
{
    protected ?string $heading = 'Orders & revenue by delivery area';

    protected ?string $description = 'Where orders are delivered (cancelled orders excluded).';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '320px';

    public ?string $filter = '30';

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
            '365' => __('Last 12 months'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $days = in_array($this->filter, ['7', '30', '90', '365'], true) ? (int) $this->filter : 30;

        $rows = Order::query()
            ->where('created_at', '>=', now()->subDays($days))
            ->where('status', '!=', OrderStatus::Cancelled)
            ->select(['city_id', DB::raw('COUNT(*) as orders_count'), DB::raw('SUM(total) as revenue')])
            ->groupBy('city_id')
            ->orderByDesc('orders_count')
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
                    'backgroundColor' => 'rgba(22, 163, 74, 0.5)',
                    'borderColor' => '#15803d',
                    'borderWidth' => 1,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => __('Revenue (EGP)'),
                    'data' => $rows->pluck('revenue')->map(fn ($v) => round((float) $v, 2))->all(),
                    'backgroundColor' => 'rgba(168, 85, 247, 0.45)',
                    'borderColor' => '#7e22ce',
                    'borderWidth' => 1,
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
                'y' => ['beginAtZero' => true, 'position' => 'left', 'ticks' => ['precision' => 0]],
                'y1' => ['beginAtZero' => true, 'position' => 'right', 'grid' => ['drawOnChartArea' => false]],
            ],
        ];
    }
}
