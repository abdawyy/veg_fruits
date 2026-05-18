<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([10, 25, 50, 100])
            ->columns([
                ImageColumn::make('display_image_url')
                    ->label(__('Image'))
                    ->checkFileExistence(false)
                    ->square()
                    ->size(48),
                TextColumn::make('sku')->searchable()->sortable(),
                TextColumn::make('name')
                    ->label(__('Name (EN)'))
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['en'] ?? '') : (string) $state),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->formatStateUsing(fn ($state, $record) => $record->category?->getTranslation('name', 'en') ?? '—'),
                TextColumn::make('price_per_kg')->sortable()->numeric(decimalPlaces: 2),
                TextColumn::make('sold_kg_total')
                    ->label(__('Sold (kg)'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('view_count')->label(__('Views'))->sortable()->numeric(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
