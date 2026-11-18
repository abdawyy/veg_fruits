<?php

namespace App\Filament\Resources\ProduceBoxes;

use App\Filament\Resources\ProduceBoxes\Pages\CreateProduceBox;
use App\Filament\Resources\ProduceBoxes\Pages\EditProduceBox;
use App\Filament\Resources\ProduceBoxes\Pages\ListProduceBoxes;
use App\Filament\Resources\ProduceBoxes\Schemas\ProduceBoxForm;
use App\Filament\Resources\ProduceBoxes\Tables\ProduceBoxesTable;
use App\Models\ProduceBox;
use App\Models\ProduceBoxView;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema as DbSchema;
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! DbSchema::hasTable('produce_box_views')) {
            return $query;
        }

        return $query
            ->selectSub(
                ProduceBoxView::query()
                    ->selectRaw('count(distinct session_id)')
                    ->whereColumn('produce_box_views.produce_box_id', 'produce_boxes.id'),
                'unique_visitors_count',
            )
            ->selectSub(
                ProduceBoxView::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('produce_box_views.produce_box_id', 'produce_boxes.id')
                    ->where('visited_at', '>=', now()->subDays(7)),
                'box_views_7d',
            )
            ->selectSub(
                ProduceBoxView::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('produce_box_views.produce_box_id', 'produce_boxes.id')
                    ->where('visited_at', '>=', now()->subDays(30)),
                'box_views_30d',
            );
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
