<?php

namespace App\Filament\Widgets\Concerns;

use App\Support\Analytics\DashboardPeriod;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

/** Reads the dashboard-wide "Period" filter; defaults to 30 days when the widget is used elsewhere. */
trait HasDashboardPeriod
{
    use InteractsWithPageFilters;

    protected function period(): DashboardPeriod
    {
        return DashboardPeriod::from($this->pageFilters['period'] ?? null);
    }
}
