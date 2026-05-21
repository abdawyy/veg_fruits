<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Models\City;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reference')->disabled(),
                Select::make('user_id')
                    ->label(__('Linked user account'))
                    ->relationship('user', null, fn ($q) => $q->orderBy('name'))
                    ->getOptionLabelFromRecordUsing(fn (User $u) => $u->name.' — '.($u->email ?? $u->phone_number ?? 'ID '.$u->id))
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Select::make('city_id')
                    ->label(__('Delivery city'))
                    ->relationship('city', null, fn ($q) => $q->orderBy('sort_order'))
                    ->getOptionLabelFromRecordUsing(fn (City $c) => $c->getTranslation('name', 'en').' — '.number_format((float) $c->shipping_fee, 2).' EGP')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('customer_phone')->maxLength(32),
                TextInput::make('customer_name')->maxLength(255),
                TextInput::make('customer_email')->email()->maxLength(255),
                Select::make('status')
                    ->options(OrderStatus::options())
                    ->required(),
                TextInput::make('shipping_address_line1')->maxLength(255)->label(__('Street address')),
                TextInput::make('shipping_address_line2')->maxLength(255)->label(__('Address line 2'))->nullable(),
                Placeholder::make('packaging_fee_help')
                    ->label('')
                    ->content(__('Order packaging_fee is an order-level snapshot (usually 0 on storefront). Per-line prep/packaging surcharges are stored on each order item.'))
                    ->columnSpanFull(),
                TextInput::make('subtotal')->disabled(),
                TextInput::make('packaging_fee')->disabled()->label(__('Order packaging fee (snapshot)')),
                TextInput::make('discount_amount')->disabled()->label(__('Discount')),
                TextInput::make('shipping_fee')->disabled()->label(__('Shipping (snapshot)')),
                TextInput::make('total')->disabled(),
                TextInput::make('invoice_path')->disabled(),
                Textarea::make('notes')->columnSpanFull(),
            ]);
    }
}
