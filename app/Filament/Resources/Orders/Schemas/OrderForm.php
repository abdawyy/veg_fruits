<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Models\City;
use App\Models\User;
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
                TextInput::make('subtotal')->disabled(),
                TextInput::make('packaging_fee')->disabled(),
                TextInput::make('shipping_fee')->disabled()->label(__('Shipping (snapshot)')),
                TextInput::make('total')->disabled(),
                TextInput::make('invoice_path')->disabled(),
                Textarea::make('notes')->columnSpanFull(),
            ]);
    }
}
