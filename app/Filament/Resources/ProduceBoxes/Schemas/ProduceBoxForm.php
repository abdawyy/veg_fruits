<?php

namespace App\Filament\Resources\ProduceBoxes\Schemas;

use App\Models\ProduceBox;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProduceBoxForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Box'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name.en')->label(__('Name (English)'))->required()->maxLength(255),
                        TextInput::make('name.ar')->label(__('Name (Arabic)'))->required()->maxLength(255),
                        TextInput::make('slug.en')->label(__('Slug (English)'))->maxLength(255),
                        TextInput::make('slug.ar')->label(__('Slug (Arabic)'))->maxLength(255),
                        Toggle::make('is_active')->default(true),
                        Toggle::make('show_card_input')
                            ->label(__('Quantity input on the home card'))
                            ->helperText(__('Show a quantity field on this box in the home carousel. Turn it off to hide the field.'))
                            ->default(true),
                        FileUpload::make('image_path')
                            ->label(__('Box image'))
                            ->image()
                            ->disk('public')
                            ->directory('boxes')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imagePreviewHeight('160')
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
                Section::make(__('Price'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('compare_at_price')
                            ->label(__('Price before sale'))
                            ->numeric()
                            ->minValue(0)
                            ->prefix('EGP')
                            ->helperText(__('Shown crossed out when it is higher than the sale price. Leave empty if the box is not on sale.')),
                        TextInput::make('price')
                            ->label(__('Sale price'))
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('EGP')
                            ->helperText(__('The price customers pay.')),
                    ]),
                Section::make(__('Description'))
                    ->columns(1)
                    ->schema([
                        Textarea::make('description.en')->label(__('Description (English)'))->rows(4),
                        Textarea::make('description.ar')->label(__('Description (Arabic)'))->rows(4),
                    ]),
                Section::make(__('SEO'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('meta_title.en')->label(__('SEO title (English)'))->maxLength(255),
                        TextInput::make('meta_title.ar')->label(__('SEO title (Arabic)'))->maxLength(255),
                        Textarea::make('meta_description.en')->label(__('SEO description (English)'))->rows(3)->columnSpanFull(),
                        Textarea::make('meta_description.ar')->label(__('SEO description (Arabic)'))->rows(3)->columnSpanFull(),
                    ]),
                Section::make(__('Contents'))
                    ->description(__('Pick products you already added. Example: Tomato, 5 kg and Cucumber, 3 kg. The shop builds that line from these rows.'))
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label(__('Product'))
                                    ->options(fn (): array => self::productOptions())
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->default(1),
                                Select::make('unit')
                                    ->label(__('Unit'))
                                    ->options([
                                        'kg' => __('Kilogram'),
                                        'gram' => __('Gram'),
                                        'piece' => __('Piece'),
                                    ])
                                    ->default('kg')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel(__('Add product'))
                            ->columnSpanFull(),
                    ]),
                Section::make(__('Analytics'))
                    ->description(__('Counts update when customers open this box on the shop.'))
                    ->columns(2)
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->schema([
                        Placeholder::make('analytics_total_views')
                            ->label(__('Total page views'))
                            ->content(fn (?ProduceBox $record): string => $record ? number_format((int) $record->view_count) : '—'),
                        Placeholder::make('analytics_unique_visitors')
                            ->label(__('Unique visitors'))
                            ->content(function (?ProduceBox $record): string {
                                if (! $record) {
                                    return '—';
                                }

                                return number_format($record->unique_visitors_count ?? $record->views()->distinct('session_id')->count('session_id'));
                            }),
                        Placeholder::make('analytics_views_7d')
                            ->label(__('Views (last 7 days)'))
                            ->content(function (?ProduceBox $record): string {
                                if (! $record) {
                                    return '—';
                                }

                                $count = $record->box_views_7d ?? $record->views()->where('visited_at', '>=', now()->subDays(7))->count();

                                return number_format((int) $count);
                            }),
                        Placeholder::make('analytics_views_30d')
                            ->label(__('Views (last 30 days)'))
                            ->content(function (?ProduceBox $record): string {
                                if (! $record) {
                                    return '—';
                                }

                                $count = $record->box_views_30d ?? $record->views()->where('visited_at', '>=', now()->subDays(30))->count();

                                return number_format((int) $count);
                            }),
                    ]),
            ]);
    }

    /**
     * @return array<int, string>
     */
    private static function productOptions(): array
    {
        return Product::query()
            ->orderBy('sku')
            ->get()
            ->mapWithKeys(function (Product $product): array {
                $label = $product->sku.' — '.$product->getTranslation('name', 'en').' / '.$product->getTranslation('name', 'ar');
                if (! $product->is_active) {
                    $label .= ' ('.__('inactive').')';
                }

                return [$product->id => $label];
            })
            ->all();
    }
}
