<?php

namespace App\Filament\Account\Resources\MyOrders\Tables;

use App\Enums\OrderStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MyOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->extremePaginationLinks()
            ->columns([
                TextColumn::make('reference')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus|string|null $state): string => $state instanceof OrderStatus
                        ? $state->label()
                        : (is_string($state) ? (OrderStatus::tryFrom($state)?->label() ?? $state) : '—')),
                TextColumn::make('total')->numeric(decimalPlaces: 2),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
