<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LatestOrdersTable;
use App\Filament\Widgets\OrdersByCityChart;
use App\Filament\Widgets\OrderStatusChart;
use App\Filament\Widgets\SalesKpiStats;
use App\Filament\Widgets\SalesTrendChart;
use App\Filament\Widgets\TopProductsByViewsChart;
use App\Filament\Widgets\TopSellingProductsChart;
use App\Support\Analytics\DashboardPeriod;
use BackedEnum;
use Filament\Forms\Components\ToggleButtons;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    public static function getNavigationLabel(): string
    {
        return __('Sales overview');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Sales overview');
    }

    public function getSubheading(): ?string
    {
        return __('Revenue, orders and best sellers for the selected period.');
    }

    public function filtersForm(Schema $schema): Schema
    {
        return self::periodFilter($schema);
    }

    public static function periodFilter(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                ToggleButtons::make('period')
                    ->hiddenLabel()
                    ->options(DashboardPeriod::options())
                    ->default(DashboardPeriod::DEFAULT)
                    ->grouped(),
            ]);
    }

    public function getColumns(): int|array
    {
        return ['default' => 1, 'lg' => 2];
    }

    public function getWidgets(): array
    {
        return [
            SalesKpiStats::class,
            SalesTrendChart::class,
            OrderStatusChart::class,
            OrdersByCityChart::class,
            TopSellingProductsChart::class,
            TopProductsByViewsChart::class,
            LatestOrdersTable::class,
        ];
    }
}
