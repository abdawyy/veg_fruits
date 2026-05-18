<?php

namespace App\Filament\Resources\ContentStrings;

use App\Filament\Resources\ContentStrings\Pages\CreateContentString;
use App\Filament\Resources\ContentStrings\Pages\EditContentString;
use App\Filament\Resources\ContentStrings\Pages\ListContentStrings;
use App\Filament\Resources\ContentStrings\Schemas\ContentStringForm;
use App\Filament\Resources\ContentStrings\Tables\ContentStringsTable;
use App\Models\ContentString;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ContentStringResource extends Resource
{
    protected static ?string $model = ContentString::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static string|UnitEnum|null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('Site copy');
    }

    public static function getModelLabel(): string
    {
        return __('String');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Site copy');
    }

    public static function form(Schema $schema): Schema
    {
        return ContentStringForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentStringsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContentStrings::route('/'),
            'create' => CreateContentString::route('/create'),
            'edit' => EditContentString::route('/{record}/edit'),
        ];
    }
}
