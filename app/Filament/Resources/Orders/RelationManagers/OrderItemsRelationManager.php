<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Line items';

    protected static bool $shouldSkipAuthorization = true;

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('product_name_snapshot')
                    ->label(__('Product'))
                    ->formatStateUsing(function ($state): string {
                        if (! is_array($state)) {
                            return '—';
                        }

                        return (string) ($state['en'] ?? $state['ar'] ?? '—');
                    }),
                TextColumn::make('quantity')->alignEnd(),
                TextColumn::make('unit'),
                TextColumn::make('unit_price')->numeric(decimalPlaces: 2),
                TextColumn::make('line_total')->numeric(decimalPlaces: 2),
                TextColumn::make('packaging'),
                TextColumn::make('services')
                    ->label(__('Services'))
                    ->formatStateUsing(fn ($state) => $state ? json_encode($state) : '—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
