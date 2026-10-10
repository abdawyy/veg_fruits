<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActivityByHourChart;
use App\Filament\Widgets\ActivityByWeekdayChart;
use App\Filament\Widgets\TopStorePathsChart;
use App\Filament\Widgets\TrafficKpiStats;
use App\Filament\Widgets\TrafficReferrersChart;
use App\Filament\Widgets\VisitorLocationsChart;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class TrafficDashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static string $routePath = 'traffic';

    protected static ?int $navigationSort = -1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function getNavigationLabel(): string
    {
        return __('Customers & traffic');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Customers & traffic');
    }

    public function getSubheading(): ?string
    {
        return __('Who visits, from where, when they are active, and how they find the shop.');
    }

    public function filtersForm(Schema $schema): Schema
    {
        return Dashboard::periodFilter($schema);
    }

    public function getColumns(): int|array
    {
        return ['default' => 1, 'lg' => 2];
    }

    public function getWidgets(): array
    {
        return [
            TrafficKpiStats::class,
            ActivityByHourChart::class,
            ActivityByWeekdayChart::class,
            VisitorLocationsChart::class,
            TrafficReferrersChart::class,
            TopStorePathsChart::class,
        ];
    }
}
