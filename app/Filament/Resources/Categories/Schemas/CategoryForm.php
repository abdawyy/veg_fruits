<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Category'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name.en')->label(__('Name (English)'))->required()->maxLength(255),
                        TextInput::make('name.ar')->label(__('Name (Arabic)'))->required()->maxLength(255),
                        TextInput::make('slug.en')->label(__('Slug (English)'))->maxLength(255),
                        TextInput::make('slug.ar')->label(__('Slug (Arabic)'))->maxLength(255),
                        Toggle::make('is_active')->default(true),
                    ]),
            ]);
    }
}
