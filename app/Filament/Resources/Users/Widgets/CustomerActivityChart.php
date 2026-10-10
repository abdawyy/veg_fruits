<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use App\Support\Analytics\CustomerInsights;
use App\Support\Analytics\TimeBuckets;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class CustomerActivityChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    public ?Model $record = null;

    public ?string $filter = 'hour';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected ?string $heading = 'This customer: browsing vs. buying times';

    public function getDescription(): ?string
    {
        return __('Page and product views compared with orders, in :tz time.', ['tz' => TimeBuckets::timezone()]);
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'hour' => __('By hour of day'),
            'weekday' => __('By weekday'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        if (! $this->record instanceof User) {
            return ['labels' => [], 'datasets' => []];
        }

        $insights = CustomerInsights::for($this->record);
        $byWeekday = $this->filter === 'weekday';

        return [
            'labels' => $byWeekday ? TimeBuckets::weekdayLabels() : TimeBuckets::hourLabels(),
            'datasets' => [
                [
                    'label' => __('Views'),
                    'data' => array_values($byWeekday ? $insights->browsingByWeekday() : $insights->browsingByHour()),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.45)',
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                    'yAxisID' => 'y',
                ],
                [
                    'type' => 'line',
                    'label' => __('Orders'),
                    'data' => array_values($byWeekday ? $insights->purchasesByWeekday() : $insights->purchasesByHour()),
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
                'y' => ['beginAtZero' => true, 'position' => 'left', 'ticks' => ['precision' => 0]],
                'y1' => ['beginAtZero' => true, 'position' => 'right', 'grid' => ['drawOnChartArea' => false], 'ticks' => ['precision' => 0]],
            ],
        ];
    }
}
