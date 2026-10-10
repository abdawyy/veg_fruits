<?php

namespace App\Filament\Widgets;

use App\Support\Analytics\TimeBuckets;
use Illuminate\Database\Eloquent\Builder;

class ActivityByWeekdayChart extends ActivityPatternChart
{
    protected ?string $heading = 'Busiest days of the week';

    protected ?string $description = 'Page views, product views and orders per weekday (Saturday first).';

    protected function bucket(Builder $query, string $column): array
    {
        return TimeBuckets::countByWeekday($query, $column);
    }

    protected function labels(): array
    {
        return TimeBuckets::weekdayLabels();
    }
}
