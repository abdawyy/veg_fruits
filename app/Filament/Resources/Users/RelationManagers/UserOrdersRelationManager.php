<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
            ->columns([
                TextColumn::make('reference')
                    ->searchable()
                    ->url(fn ($record) => OrderResource::getUrl('edit', ['record' => $record])),
                TextColumn::make('customer_email')->label(__('Order email'))->placeholder('—'),
                TextColumn::make('customer_phone'),
                TextColumn::make('status')->badge(),
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
