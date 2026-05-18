<?php

namespace App\Filament\Resources\PackagingTypes;

use App\Filament\Resources\PackagingTypes\Pages\CreatePackagingType;
use App\Filament\Resources\PackagingTypes\Pages\EditPackagingType;
use App\Filament\Resources\PackagingTypes\Pages\ListPackagingTypes;
use App\Filament\Resources\PackagingTypes\Schemas\PackagingTypeForm;
use App\Filament\Resources\PackagingTypes\Tables\PackagingTypesTable;
use App\Models\PackagingType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PackagingTypeResource extends Resource
{
    protected static ?string $model = PackagingType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return __('Packaging types');
    }

    public static function getModelLabel(): string
    {
        return __('Packaging type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Packaging types');
    }

    public static function form(Schema $schema): Schema
    {
        return PackagingTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackagingTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPackagingTypes::route('/'),
            'create' => CreatePackagingType::route('/create'),
            'edit' => EditPackagingType::route('/{record}/edit'),
        ];
    }
}
