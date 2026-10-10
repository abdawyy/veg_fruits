<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\OrderStatus;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('latestSiteVisitor'))
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable()->placeholder('—'),
                TextColumn::make('phone_number')->label(__('Phone'))->searchable()->placeholder('—'),
                IconColumn::make('is_admin')->label(__('Admin'))->boolean(),
                TextColumn::make('roles.name')
                    ->label(__('Roles'))
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('orders_count')
                    ->counts('orders')
                    ->label(__('Orders'))
                    ->sortable(),
                TextColumn::make('orders_sum_total')
                    ->sum(['orders' => fn (Builder $query) => $query->where('status', '!=', OrderStatus::Cancelled)], 'total')
                    ->label(__('Total spent (EGP)'))
                    ->numeric(decimalPlaces: 2)
                    ->placeholder('0.00')
                    ->sortable(),
                TextColumn::make('orders_max_created_at')
                    ->max('orders', 'created_at')
                    ->label(__('Last order'))
                    ->dateTime()
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('location')
                    ->label(__('Location'))
                    ->state(fn (User $record): ?string => $record->latestSiteVisitor?->location_label)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('site_visitors_max_last_seen_at')
                    ->max('siteVisitors', 'last_seen_at')
                    ->label(__('Last visit'))
                    ->since()
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
