<?php

namespace App\Filament\Resources\SeoSettings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SeoSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Homepage'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('home_meta_title_en')->maxLength(255),
                        TextInput::make('home_meta_title_ar')->maxLength(255),
                        Textarea::make('home_meta_description_en')->rows(3)->columnSpanFull(),
                        Textarea::make('home_meta_description_ar')->rows(3)->columnSpanFull(),
                    ]),
                Section::make(__('Shop catalog'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('shop_meta_title_en')->maxLength(255),
                        TextInput::make('shop_meta_title_ar')->maxLength(255),
                        Textarea::make('shop_meta_description_en')->rows(3)->columnSpanFull(),
                        Textarea::make('shop_meta_description_ar')->rows(3)->columnSpanFull(),
                    ]),
                Section::make(__('Special services page'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('services_meta_title_en')->maxLength(255),
                        TextInput::make('services_meta_title_ar')->maxLength(255),
                        Textarea::make('services_meta_description_en')->rows(3)->columnSpanFull(),
                        Textarea::make('services_meta_description_ar')->rows(3)->columnSpanFull(),
                    ]),
                Section::make(__('Product pages'))
                    ->description(__('Appended after product name in the browser title.'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('product_meta_title_suffix_en')->maxLength(255),
                        TextInput::make('product_meta_title_suffix_ar')->maxLength(255),
                    ]),
                Section::make(__('Open Graph'))
                    ->schema([
                        TextInput::make('og_image_url')->url()->maxLength(2048)->columnSpanFull(),
                    ]),
            ]);
    }
}
