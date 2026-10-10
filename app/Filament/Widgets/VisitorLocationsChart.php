<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisitor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VisitorLocationsChart extends ChartWidget
{
    protected ?string $heading = 'Where visitors open the shop from';

    protected ?string $description = 'Visitor sessions by location (last 30 days). Located once per session from the IP or Cloudflare headers — raw IPs are not stored.';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '360px';

    public ?string $filter = 'city';

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

        $field = in_array($this->filter, ['city', 'region', 'country'], true) ? $this->filter : 'city';
        $groupColumns = array_values(array_unique([$field, 'country']));

        $rows = SiteVisitor::query()
            ->where('last_seen_at', '>=', now()->subDays(30))
            ->whereNotNull('geo_resolved_at')
            ->select([...$groupColumns, DB::raw('COUNT(*) as sessions')])
            ->groupBy($groupColumns)
            ->orderByDesc('sessions')
            ->limit(15)
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
                    'backgroundColor' => 'rgba(20, 184, 166, 0.5)',
                    'borderColor' => '#0f766e',
                    'borderWidth' => 1,
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
            'scales' => [
                'x' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
            ],
        ];
    }
}
