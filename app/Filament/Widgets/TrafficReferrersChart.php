<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisitor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class TrafficReferrersChart extends ChartWidget
{
    protected ?string $heading = 'Traffic sources (first touch, 14 days)';

    protected ?string $description = 'Host extracted from the HTTP Referer on the visitor’s first page view.';

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
        if (! Schema::hasColumn('site_visitors', 'referrer')) {
            return [
                'labels' => [],
                'datasets' => [
                    ['label' => __('Sessions'), 'data' => []],
                ],
            ];
        }

        $hosts = SiteVisitor::query()
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->where('first_seen_at', '>=', now()->subDays(14))
            ->limit(8000)
            ->pluck('referrer')
            ->map(function (string $ref): string {
                $host = parse_url($ref, PHP_URL_HOST);

                return is_string($host) && $host !== '' ? $host : '(unknown)';
            })
            ->countBy()
            ->sortDesc()
            ->take(12);

        return [
            'labels' => $hosts->keys()->all(),
            'datasets' => [
                [
                    'label' => __('Sessions'),
                    'data' => $hosts->values()->all(),
                    'backgroundColor' => 'rgba(249, 115, 22, 0.55)',
                    'borderColor' => '#c2410c',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}
