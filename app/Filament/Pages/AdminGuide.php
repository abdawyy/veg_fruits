<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AdminGuide extends Page
{
    protected static ?string $navigationLabel = 'Admin guide';

    protected static ?int $navigationSort = -100;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    public function getTitle(): string
    {
        return 'Where is everything?';
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                View::make('filament.pages.admin-guide'),
            ]);
    }
}
