<?php

namespace App\Filament\Account\Resources\MyOrders\Schemas;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MyOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Order'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('reference'),
                        TextEntry::make('status')
                            ->formatStateUsing(fn (OrderStatus|string|null $state): string => $state instanceof OrderStatus
                                ? $state->label()
                                : (is_string($state) ? (OrderStatus::tryFrom($state)?->label() ?? $state) : '—')),
                        TextEntry::make('customer_phone'),
                        TextEntry::make('customer_email')->placeholder('—'),
                        TextEntry::make('city_id')
                            ->label(__('City'))
                            ->formatStateUsing(fn ($state, Order $record): string => $record->city?->getTranslation('name', app()->getLocale()) ?? '—'),
                        TextEntry::make('shipping_address_line1')->label(__('Street'))->placeholder('—'),
                        TextEntry::make('shipping_address_line2')->label(__('Details'))->placeholder('—'),
                        TextEntry::make('subtotal'),
                        TextEntry::make('packaging_fee'),
                        TextEntry::make('shipping_fee')->label(__('Shipping')),
                        TextEntry::make('total'),
                        TextEntry::make('created_at')->dateTime(),
                    ]),
            ]);
    }
}
