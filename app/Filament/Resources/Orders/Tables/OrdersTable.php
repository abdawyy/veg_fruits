<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'city']))
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([10, 25, 50, 100])
            ->columns([
                TextColumn::make('reference')->searchable()->sortable(),
                TextColumn::make('user.email')
                    ->label(__('User email'))
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('customer_email')
                    ->label(__('Guest email'))
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('customer_phone')->searchable(),
                TextColumn::make('city')
                    ->label(__('City'))
                    ->formatStateUsing(fn ($state, $record) => $record->city?->getTranslation('name', 'en') ?? '—'),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => OrderStatus::tryFrom((string) $state)?->label() ?? (string) $state),
                TextColumn::make('total')->sortable(),
                TextColumn::make('payment_gateway')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime()->sortable(),
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
