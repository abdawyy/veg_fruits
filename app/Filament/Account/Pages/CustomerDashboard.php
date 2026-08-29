<?php

namespace App\Filament\Account\Pages;

use BackedEnum;
use Filament\Pages\Dashboard;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CustomerDashboard extends Dashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public static function getNavigationLabel(): string
    {
        return __('aldawy.account_nav_home');
    }

    public function getTitle(): string
    {
        return __('aldawy.account_nav_home');
    }

    public function getHeading(): string
    {
        return __('aldawy.account_dashboard_heading');
    }

    public function getSubheading(): ?string
    {
        return __('aldawy.account_dashboard_sub');
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                View::make('filament.account.pages.customer-home'),
            ]);
    }
}
