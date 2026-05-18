<?php

namespace App\Filament\Resources\PreparationServices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PreparationServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Preparation service'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')->required()->maxLength(64)->unique(ignoreRecord: true),
                        TextInput::make('name.en')->label(__('Name (English)'))->required()->maxLength(255),
                        TextInput::make('name.ar')->label(__('Name (Arabic)'))->required()->maxLength(255),
                        TextInput::make('surcharge_amount')->numeric()->required()->default(0),
                        Toggle::make('surcharge_is_percent')->label(__('Surcharge is percent'))->default(false),
                        TextInput::make('sort_order')->numeric()->default(0)->required(),
                        Toggle::make('is_active')->default(true),
                    ]),
            ]);
    }
}
