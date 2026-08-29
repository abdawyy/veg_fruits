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
        $currency = config('aldawy.currency', 'EGP');

        return $schema
            ->components([
                Section::make(__('aldawy.account_order_summary'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('reference')
                            ->label(__('aldawy.account_order_ref'))
                            ->copyable()
                            ->weight('bold'),
                        TextEntry::make('status')
                            ->label(__('aldawy.account_order_status'))
                            ->badge()
                            ->formatStateUsing(fn (OrderStatus|string|null $state): string => $state instanceof OrderStatus
                                ? $state->label()
                                : (is_string($state) ? (OrderStatus::tryFrom($state)?->label() ?? $state) : '—')),
                        TextEntry::make('customer_phone')
                            ->label(__('aldawy.checkout_phone')),
                        TextEntry::make('customer_email')
                            ->label(__('aldawy.checkout_email'))
                            ->placeholder('—'),
                        TextEntry::make('created_at')
                            ->label(__('aldawy.account_order_date'))
                            ->dateTime()
                            ->columnSpanFull(),
                    ]),
                Section::make(__('aldawy.invoice_address'))
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('city_id')
                            ->label(__('aldawy.checkout_city'))
                            ->formatStateUsing(fn ($state, Order $record): string => $record->city?->getTranslation('name', app()->getLocale()) ?? '—'),
                        TextEntry::make('shipping_address_line1')
                            ->label(__('aldawy.checkout_address1'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('shipping_address_line2')
                            ->label(__('aldawy.checkout_address2'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
                Section::make(__('aldawy.checkout_summary'))
                    ->icon('heroicon-o-banknotes')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label(__('aldawy.checkout_items_subtotal'))
                            ->formatStateUsing(fn ($state): string => number_format((float) $state, 2).' '.$currency),
                        TextEntry::make('discount_amount')
                            ->label(__('aldawy.checkout_discount'))
                            ->formatStateUsing(fn ($state): string => number_format((float) $state, 2).' '.$currency)
                            ->visible(fn (Order $record): bool => bccomp((string) $record->discount_amount, '0', 4) > 0),
                        TextEntry::make('packaging_fee')
                            ->label(__('aldawy.cart_line_pack'))
                            ->formatStateUsing(fn ($state): string => number_format((float) $state, 2).' '.$currency),
                        TextEntry::make('shipping_fee')
                            ->label(__('aldawy.checkout_shipping'))
                            ->formatStateUsing(fn ($state): string => number_format((float) $state, 2).' '.$currency),
                        TextEntry::make('total')
                            ->label(__('aldawy.invoice_total'))
                            ->weight('bold')
                            ->size('lg')
                            ->color('primary')
                            ->formatStateUsing(fn ($state): string => number_format((float) $state, 2).' '.$currency)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
