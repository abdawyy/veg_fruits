<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\SiteVisitor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VisitorLocationsChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected ?string $maxHeight = '300px';

    public ?string $filter = 'city';

    public function getHeading(): string
    {
        return __('Where visitors are');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('located per session, raw IPs are not stored');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'city' => __('By city'),
            'region' => __('By region / governorate'),
            'country' => __('By country'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        if (! Schema::hasColumn('site_visitors', 'country')) {
            return ['labels' => [], 'datasets' => [['label' => __('Sessions'), 'data' => []]]];
        }

        $period = $this->period();
        $field = in_array($this->filter, ['city', 'region', 'country'], true) ? $this->filter : 'city';
        $groupColumns = array_values(array_unique([$field, 'country']));

        $rows = SiteVisitor::query()
            ->whereBetween('last_seen_at', [$period->start(), $period->end()])
            ->whereNotNull('geo_resolved_at')
            ->select([...$groupColumns, DB::raw('COUNT(*) as sessions')])
            ->groupBy($groupColumns)
            ->orderByDesc('sessions')
            ->limit(10)
            ->get();

        $labels = $rows->map(function (SiteVisitor $row) use ($field): string {
            $value = $row->getAttribute($field);
            if (! is_string($value) || $value === '') {
                return $row->country ? $row->country.' — '.__('Unknown') : __('Unknown');
            }

            return $field === 'country' || ! $row->country ? $value : $value.', '.$row->country;
        })->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Sessions'),
                    'data' => $rows->pluck('sessions')->map(fn ($v) => (int) $v)->all(),
                    'backgroundColor' => 'rgba(20, 184, 166, 0.6)',
                    'borderColor' => '#0f766e',
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
