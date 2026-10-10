<?php

namespace App\Filament\Widgets\Concerns;

use BackedEnum;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * KPI card with "vs previous period" change, trend arrow, colour and optional sparkline.
 * Requires HasDashboardPeriod on the widget.
 */
trait BuildsKpiStats
{
    /**
     * @param  list<int|float>|null  $sparkline
     */
    protected function kpi(
        string $label,
        string $formattedValue,
        float|int $current,
        float|int $previous,
        ?array $sparkline = null,
        string|BackedEnum|null $icon = null,
        bool $higherIsBetter = true,
    ): Stat {
        $stat = Stat::make($label, $formattedValue)->icon($icon);
        $comparison = $this->period()->comparisonLabel();

        if ((float) $previous === 0.0) {
            $stat->description(((float) $current > 0 ? __('New') : __('No change')).' '.$comparison)
                ->color('gray');
        } else {
            $change = (($current - $previous) / abs($previous)) * 100;
            $up = $change >= 0;
            $good = $up === $higherIsBetter;

            $stat->description(($up ? '+' : '').number_format($change, 1).'% '.$comparison)
                ->descriptionIcon($up ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color(abs($change) < 0.05 ? 'gray' : ($good ? 'success' : 'danger'));
        }

        if ($sparkline !== null && count($sparkline) > 1) {
            $stat->chart($sparkline);
        }

        return $stat;
    }
}
