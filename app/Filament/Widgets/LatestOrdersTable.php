<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrdersTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Latest orders'))
            ->description(__('Newest 8 orders — click a row to open it.'))
            ->query(fn (): Builder => Order::query()->with(['user', 'city'])->latest('created_at'))
            ->paginated(false)
            ->poll('60s')
            ->modifyQueryUsing(fn (Builder $query) => $query->limit(8))
            ->recordUrl(fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('reference')
                    ->label(__('Order'))
                    ->weight('semibold')
                    ->copyable(),
                TextColumn::make('customer')
                    ->label(__('Customer'))
                    ->state(fn (Order $record): string => $record->user?->name ?: ($record->customer_name ?: __('Guest')))
                    ->description(fn (Order $record): ?string => $record->customer_phone),
                TextColumn::make('city')
                    ->label(__('Area'))
                    ->state(fn (Order $record): string => $record->city?->getTranslation('name', app()->getLocale()) ?? '—'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (?OrderStatus $state): string => $state?->label() ?? '—')
                    ->color(fn (?OrderStatus $state): string => match ($state) {
                        OrderStatus::Pending => 'warning',
                        OrderStatus::Confirmed, OrderStatus::Processing => 'info',
                        OrderStatus::Shipped => 'primary',
                        OrderStatus::Delivered => 'success',
                        OrderStatus::Cancelled => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('total')
                    ->label(__('Total'))
                    ->formatStateUsing(fn ($state): string => 'EGP '.number_format((float) $state, 2))
                    ->alignEnd(),
                TextColumn::make('created_at')
                    ->label(__('Placed'))
                    ->since()
                    ->tooltip(fn (Order $record): string => $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->alignEnd(),
            ]);
    }
}
