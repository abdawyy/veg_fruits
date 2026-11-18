<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasDashboardPeriod;
use App\Models\ProduceBox;
use App\Models\ProduceBoxView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TopBoxesByViewsChart extends ChartWidget
{
    use HasDashboardPeriod;

    protected ?string $maxHeight = '300px';

    public function getHeading(): string
    {
        return __('Most viewed boxes');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('box page opens');
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
        if (! Schema::hasTable('produce_box_views')) {
            return ['labels' => [], 'datasets' => [['label' => __('Views'), 'data' => []]]];
        }

        $period = $this->period();

        $rows = ProduceBoxView::query()
            ->whereBetween('visited_at', [$period->start(), $period->end()])
            ->select(['produce_box_id', DB::raw('COUNT(*) as views')])
            ->groupBy('produce_box_id')
            ->orderByDesc('views')
            ->limit(8)
            ->get();

        $boxes = ProduceBox::query()->whereIn('id', $rows->pluck('produce_box_id'))->get(['id', 'name'])->keyBy('id');
        $locale = app()->getLocale();

        $labels = $rows->map(function ($row) use ($boxes, $locale): string {
            $box = $boxes->get($row->produce_box_id);
            $name = $box
                ? ($box->getTranslation('name', $locale) ?: $box->getTranslation('name', 'en'))
                : null;
            $name = $name ?: '#'.$row->produce_box_id;

            return mb_strlen($name) > 28 ? mb_substr($name, 0, 25).'…' : $name;
        })->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Views'),
                    'data' => $rows->pluck('views')->map(fn ($v) => (int) $v)->all(),
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
                'x' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
                'y' => ['grid' => ['display' => false]],
            ],
        ];
    }
}
