<?php

namespace App\Filament\Widgets;

use App\Support\Analytics\TimeBuckets;
use Illuminate\Database\Eloquent\Builder;

class ActivityByWeekdayChart extends ActivityPatternChart
{
    protected int|string|array $columnSpan = 1;

    public function getHeading(): string
    {
        return __('Busiest days');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('Saturday first');
    }

    protected function bucket(Builder $query, string $column): array
    {
        return TimeBuckets::countByWeekday($query, $column);
    }

    protected function labels(): array
    {
        return TimeBuckets::weekdayLabels();
    }
}
