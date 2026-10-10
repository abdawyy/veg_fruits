<?php

namespace App\Filament\Widgets;

use App\Support\Analytics\TimeBuckets;
use Illuminate\Database\Eloquent\Builder;

class ActivityByHourChart extends ActivityPatternChart
{
    public function getHeading(): string
    {
        return __('When customers visit & buy');
    }

    public function getDescription(): string
    {
        return $this->period()->label().' · '.__('by hour of day').' · '.TimeBuckets::timezone().' · '.__('use it to time ads, SMS and promos');
    }

    protected function bucket(Builder $query, string $column): array
    {
        return TimeBuckets::countByHour($query, $column);
    }

    protected function labels(): array
    {
        return TimeBuckets::hourLabels();
    }
}
