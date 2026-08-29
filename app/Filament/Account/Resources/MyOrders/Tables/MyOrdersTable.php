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
        $currency = config('aldawy.currency', 'EGP');

        return $table
            ->heading(__('aldawy.account_tile_orders'))
            ->description(__('aldawy.account_orders_table_sub'))
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->extremePaginationLinks()
            ->emptyStateHeading(__('aldawy.account_no_orders'))
            ->emptyStateDescription(__('aldawy.account_no_orders_sub'))
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->columns([
                TextColumn::make('reference')
                    ->label(__('aldawy.account_order_ref'))
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->fontFamily('mono'),
                TextColumn::make('status')
                    ->label(__('aldawy.account_order_status'))
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus|string|null $state): string => $state instanceof OrderStatus
                        ? $state->label()
                        : (is_string($state) ? (OrderStatus::tryFrom($state)?->label() ?? $state) : '—'))
                    ->color(fn (OrderStatus|string|null $state): string => self::statusColor($state)),
                TextColumn::make('total')
                    ->label(__('aldawy.invoice_total'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' '.$currency)
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('created_at')
                    ->label(__('aldawy.account_order_date'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make()->label(__('aldawy.account_view_order')),
            ])
            ->toolbarActions([]);
    }

    private static function statusColor(OrderStatus|string|null $state): string
    {
        $status = $state instanceof OrderStatus ? $state : OrderStatus::tryFrom((string) $state);

        return match ($status) {
            OrderStatus::Pending, OrderStatus::Processing => 'warning',
            OrderStatus::Confirmed, OrderStatus::Shipped => 'primary',
            OrderStatus::Delivered => 'success',
            OrderStatus::Cancelled => 'gray',
            default => 'gray',
        };
    }
}
