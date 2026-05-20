<?php

namespace App\Filament\Resources\ProduceBoxes;

use App\Filament\Resources\ProduceBoxes\Pages\CreateProduceBox;
use App\Filament\Resources\ProduceBoxes\Pages\EditProduceBox;
use App\Filament\Resources\ProduceBoxes\Pages\ListProduceBoxes;
use App\Filament\Resources\ProduceBoxes\Schemas\ProduceBoxForm;
use App\Filament\Resources\ProduceBoxes\Tables\ProduceBoxesTable;
use App\Models\ProduceBox;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProduceBoxResource extends Resource
{
    protected static ?string $model = ProduceBox::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('Produce boxes');
    }

    public static function getModelLabel(): string
    {
        return __('Produce box');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Produce boxes');
    }

    public static function form(Schema $schema): Schema
    {
        return ProduceBoxForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduceBoxesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProduceBoxes::route('/'),
            'create' => CreateProduceBox::route('/create'),
            'edit' => EditProduceBox::route('/{record}/edit'),
        ];
    }
}
