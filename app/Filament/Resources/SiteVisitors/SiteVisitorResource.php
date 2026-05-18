<?php

namespace App\Filament\Resources\SiteVisitors;

use App\Filament\Resources\SiteVisitors\Pages\ListSiteVisitors;
use App\Filament\Resources\SiteVisitors\Tables\SiteVisitorsTable;
use App\Models\SiteVisitor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SiteVisitorResource extends Resource
{
    protected static ?string $model = SiteVisitor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignal;

    protected static string|UnitEnum|null $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 0;

    public static function getNavigationLabel(): string
    {
        return __('Visitors');
    }

    public static function getModelLabel(): string
    {
        return __('Visitor');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Visitors');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return SiteVisitorsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteVisitors::route('/'),
        ];
    }
}
