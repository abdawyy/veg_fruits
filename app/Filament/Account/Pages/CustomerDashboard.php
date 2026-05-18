<?php

namespace App\Filament\Account\Pages;

use Filament\Pages\Dashboard;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class CustomerDashboard extends Dashboard
{
    public static function getNavigationLabel(): string
    {
        return __('aldawy.account_nav_home');
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
