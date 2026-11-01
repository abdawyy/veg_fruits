<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Support\TableSearch;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Orders';

    protected static bool $shouldSkipAuthorization = true;

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->searchable()
            ->searchUsing(fn (Builder $query, string $search): Builder => TableSearch::apply($query, $search, [
                'reference',
                'customer_name',
                'customer_email',
                'customer_phone',
                'notes',
            ]))
            ->columns([
                TextColumn::make('reference')
                    ->searchable()
                    ->url(fn ($record) => OrderResource::getUrl('edit', ['record' => $record])),
                TextColumn::make('customer_email')->label(__('Order email'))->placeholder('—'),
                TextColumn::make('customer_phone'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus|string|null $state): string => $state instanceof OrderStatus
                        ? $state->label()
                        : (is_string($state) ? (OrderStatus::tryFrom($state)?->label() ?? $state) : '—')),
                TextColumn::make('total')->numeric(decimalPlaces: 2),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                //
            ])
            ->headerActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
