<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Actions\DownloadOrderInvoiceAction;
use App\Filament\Support\TableSearch;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->searchUsing(fn (Builder $query, string $search): Builder => TableSearch::apply($query, $search, [
                'reference',
                'customer_name',
                'customer_email',
                'customer_phone',
                'notes',
                'user.name',
                'user.email',
                'user.phone_number',
                'city.name',
                'items.product_name_snapshot',
                'items.product.name',
                'items.product.sku',
                'items.produceBox.name',
            ]))
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'city']))
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([10, 25, 50, 100])
            ->columns([
                TextColumn::make('reference')->searchable()->sortable(),
                TextColumn::make('customer_name')
                    ->label(__('Customer'))
                    ->placeholder('—')
                    ->toggleable(),
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
                    ->formatStateUsing(fn (OrderStatus|string|null $state): string => $state instanceof OrderStatus
                        ? $state->label()
                        : (is_string($state) ? (OrderStatus::tryFrom($state)?->label() ?? $state) : '—')),
                TextColumn::make('total')->sortable(),
                TextColumn::make('payment_gateway')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                DownloadOrderInvoiceAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
