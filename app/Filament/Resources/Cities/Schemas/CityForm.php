<?php

namespace App\Filament\Resources\Cities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('City'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')->required()->maxLength(64)->unique(ignoreRecord: true),
                        TextInput::make('name.en')->label(__('Name (English)'))->required()->maxLength(255),
                        TextInput::make('name.ar')->label(__('Name (Arabic)'))->required()->maxLength(255),
                        TextInput::make('shipping_fee')->label(__('Shipping fee (EGP)'))->numeric()->required()->default(0),
                        TextInput::make('sort_order')->numeric()->default(0)->required(),
                        Toggle::make('is_active')->default(true),
                    ]),
            ]);
    }
}
