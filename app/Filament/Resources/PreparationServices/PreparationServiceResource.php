<?php

namespace App\Filament\Resources\PreparationServices;

use App\Filament\Resources\PreparationServices\Pages\CreatePreparationService;
use App\Filament\Resources\PreparationServices\Pages\EditPreparationService;
use App\Filament\Resources\PreparationServices\Pages\ListPreparationServices;
use App\Filament\Resources\PreparationServices\Schemas\PreparationServiceForm;
use App\Filament\Resources\PreparationServices\Tables\PreparationServicesTable;
use App\Models\PreparationService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PreparationServiceResource extends Resource
{
    protected static ?string $model = PreparationService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('Preparation services');
    }

    public static function getModelLabel(): string
    {
        return __('Preparation service');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Preparation services');
    }

    public static function form(Schema $schema): Schema
    {
        return PreparationServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PreparationServicesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPreparationServices::route('/'),
            'create' => CreatePreparationService::route('/create'),
            'edit' => EditPreparationService::route('/{record}/edit'),
        ];
    }
}
