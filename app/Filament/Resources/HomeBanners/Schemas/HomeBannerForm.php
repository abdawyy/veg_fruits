<?php

namespace App\Filament\Resources\HomeBanners\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Visibility & order'))
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_active')->default(true),
                        TextInput::make('sort_order')->numeric()->default(0)->required(),
                        DateTimePicker::make('starts_at')->seconds(false),
                        DateTimePicker::make('ends_at')->seconds(false),
                    ]),
                Section::make(__('Arabic / English copy'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('title.en')->label(__('Title (English)'))->required()->maxLength(255),
                        TextInput::make('title.ar')->label(__('Title (Arabic)'))->required()->maxLength(255),
                        TextInput::make('subtitle.en')->label(__('Subtitle (English)'))->maxLength(500),
                        TextInput::make('subtitle.ar')->label(__('Subtitle (Arabic)'))->maxLength(500),
                        TextInput::make('badge_text.en')->label(__('Badge (English)'))->maxLength(120),
                        TextInput::make('badge_text.ar')->label(__('Badge (Arabic)'))->maxLength(120),
                        TextInput::make('cta_label.en')->label(__('Button (English)'))->maxLength(120),
                        TextInput::make('cta_label.ar')->label(__('Button (Arabic)'))->maxLength(120),
                    ]),
                Section::make(__('Links & media'))
                    ->columns(1)
                    ->schema([
                        TextInput::make('cta_url')->url()->maxLength(2048)->placeholder('/shop?q=mango'),
                        FileUpload::make('image_path')
                            ->label(__('Banner image (upload)'))
                            ->image()
                            ->disk('public')
                            ->directory('home-banners')
                            ->maxSize(5120)
                            ->nullable()
                            ->imageEditor()
                            ->helperText(__('Uploaded image is shown before the external URL below.')),
                        TextInput::make('image_url')->url()->maxLength(2048)->label(__('Banner image URL (optional)')),
                        Textarea::make('hot_product_skus')
                            ->label(__('Hot product SKUs (comma-separated)'))
                            ->helperText(__('Example: FRU-022, VEG-153, FRU-024'))
                            ->rows(2),
                    ]),
                Section::make(__('Rainbow strip (CSS gradient)'))
                    ->columns(3)
                    ->schema([
                        ColorPicker::make('gradient_from')->default('#f97316'),
                        ColorPicker::make('gradient_mid')->default('#eab308'),
                        ColorPicker::make('gradient_to')->default('#22c55e'),
                    ]),
            ]);
    }
}
