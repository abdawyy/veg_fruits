<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\SiteVisitor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class TrafficReferrersChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Traffic sources');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('first-touch referrer');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        if (! Schema::hasColumn('site_visitors', 'referrer')) {
            return ['labels' => [], 'datasets' => [['label' => __('Sessions'), 'data' => []]]];
        }

        $period = $this->period();

        $visitors = SiteVisitor::query()->whereBetween('first_seen_at', [$period->start(), $period->end()]);

        $direct = (clone $visitors)
            ->where(fn ($query) => $query->whereNull('referrer')->orWhere('referrer', ''))
            ->count();

        $hosts = (clone $visitors)
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->limit(8000)
            ->pluck('referrer')
            ->map(function (string $ref): string {
                $host = parse_url($ref, PHP_URL_HOST);

                return is_string($host) && $host !== '' ? preg_replace('/^www\./', '', $host) : __('Unknown');
            })
            ->countBy()
            ->sortDesc();

        $top = $hosts->take(6);
        $other = $hosts->slice(6)->sum();

        $series = collect([__('Direct / none') => $direct])->merge($top);
        if ($other > 0) {
            $series->put(__('Other'), $other);
        }
        $series = $series->filter(fn (int $count) => $count > 0);

        return [
            'labels' => $series->keys()->all(),
            'datasets' => [
                [
                    'label' => __('Sessions'),
                    'data' => $series->values()->all(),
                    'backgroundColor' => ['#94a3b8', '#f97316', '#3b82f6', '#16a34a', '#a855f7', '#eab308', '#14b8a6', '#64748b'],
                    'borderWidth' => 0,
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
            'cutout' => '62%',
            'plugins' => ['legend' => ['position' => 'right']],
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
        ];
    }
}
