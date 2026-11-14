<?php

namespace App\Filament\Resources\ProduceBoxes\Tables;

use App\Filament\Support\TableSearch;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProduceBoxesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->searchUsing(fn (Builder $query, string $search): Builder => TableSearch::apply($query, $search, [
                'name',
                'slug',
            ]))
            ->columns([
                ImageColumn::make('cover_image_url')
                    ->label(__('Image'))
                    ->checkFileExistence(false)
                    ->square()
                    ->size(48),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->formatStateUsing(fn ($state, $record) => $record->getTranslation('name', 'en')),
                TextColumn::make('compare_at_price')
                    ->label(__('Before sale'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' EGP')
                    ->placeholder('—'),
                TextColumn::make('price')->label(__('Sale price'))->numeric(decimalPlaces: 2)->suffix(' EGP'),
                TextColumn::make('items_count')->counts('items')->label(__('Items')),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
