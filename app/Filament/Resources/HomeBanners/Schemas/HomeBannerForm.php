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
                    ->schema(self::visibilitySchema()),
                Section::make(__('Arabic / English copy'))
                    ->columns(2)
                    ->schema(self::copySchema()),
                Section::make(__('Links & media'))
                    ->columns(1)
                    ->schema(self::mediaSchema()),
                Section::make(__('Rainbow strip (CSS gradient)'))
                    ->columns(3)
                    ->schema(self::gradientSchema()),
            ]);
    }

    /**
     * Fields for the “create multiple” repeater (one banner per item).
     *
     * @return list<\Filament\Forms\Components\Component>
     */
    public static function repeaterItemSchema(): array
    {
        return [
            ...self::visibilitySchema(includeSortOrder: false),
            ...self::copySchema(),
            ...self::mediaSchema(),
            ...self::gradientSchema(),
        ];
    }

    /**
     * @return list<\Filament\Forms\Components\Component>
     */
    private static function visibilitySchema(bool $includeSortOrder = true): array
    {
        $fields = [
            Toggle::make('is_active')->default(true),
            DateTimePicker::make('starts_at')->seconds(false),
            DateTimePicker::make('ends_at')->seconds(false),
        ];

        if ($includeSortOrder) {
            array_unshift($fields, TextInput::make('sort_order')->numeric()->default(0)->required());
        }

        return $fields;
    }

    /**
     * @return list<\Filament\Forms\Components\Component>
     */
    private static function copySchema(): array
    {
        return [
            TextInput::make('title.en')->label(__('Title (English)'))->required()->maxLength(255),
            TextInput::make('title.ar')->label(__('Title (Arabic)'))->required()->maxLength(255),
            TextInput::make('subtitle.en')->label(__('Subtitle (English)'))->maxLength(500),
            TextInput::make('subtitle.ar')->label(__('Subtitle (Arabic)'))->maxLength(500),
            TextInput::make('badge_text.en')->label(__('Badge (English)'))->maxLength(120),
            TextInput::make('badge_text.ar')->label(__('Badge (Arabic)'))->maxLength(120),
            TextInput::make('cta_label.en')->label(__('Button (English)'))->maxLength(120),
            TextInput::make('cta_label.ar')->label(__('Button (Arabic)'))->maxLength(120),
        ];
    }

    /**
     * @return list<\Filament\Forms\Components\Component>
     */
    private static function mediaSchema(): array
    {
        return [
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
        ];
    }

    /**
     * @return list<\Filament\Forms\Components\Component>
     */
    private static function gradientSchema(): array
    {
        return [
            ColorPicker::make('gradient_from')->default('#f97316'),
            ColorPicker::make('gradient_mid')->default('#eab308'),
            ColorPicker::make('gradient_to')->default('#22c55e'),
        ];
    }
}
