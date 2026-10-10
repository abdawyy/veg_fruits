<?php

namespace App\Filament\Widgets;

use App\Support\Analytics\TimeBuckets;
use Illuminate\Database\Eloquent\Builder;

class ActivityByHourChart extends ActivityPatternChart
{
    protected ?string $heading = 'When customers visit & buy (hour of day)';

    public function getDescription(): ?string
    {
        return __('Page views, product views and orders per hour, in :tz time. Use it to time ads, SMS and promos.', [
            'tz' => TimeBuckets::timezone(),
        ]);
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
