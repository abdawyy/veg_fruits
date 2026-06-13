<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\PackagingType;
use App\Models\PreparationService;
use App\Models\Product;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Core'))
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', null, fn ($query) => $query->orderBy('id'))
                            ->getOptionLabelFromRecordUsing(fn (Category $c) => $c->getTranslation('name', 'en').' / '.$c->getTranslation('name', 'ar'))
                            ->searchable()
                            ->required(),
                        TextInput::make('sku')
                            ->label(__('SKU'))
                            ->required()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),
                        Toggle::make('is_active')->default(true),
                        Toggle::make('track_stock')->label(__('Track stock')),
                        TextInput::make('stock_quantity')
                            ->numeric()
                            ->label(__('Stock quantity'))
                            ->visible(fn ($get) => (bool) $get('track_stock')),
                        Toggle::make('sell_by_piece')->label(__('Sell by piece')),
                    ]),
                Section::make(__('Names & slugs'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name.en')->label(__('Name (English)'))->required()->maxLength(255),
                        TextInput::make('name.ar')->label(__('Name (Arabic)'))->required()->maxLength(255),
                        TextInput::make('slug.en')->label(__('Slug (English)'))->maxLength(255),
                        TextInput::make('slug.ar')->label(__('Slug (Arabic)'))->maxLength(255),
                    ]),
                Section::make(__('Descriptions'))
                    ->columns(1)
                    ->schema([
                        Textarea::make('description.en')->label(__('Description (English)'))->rows(3),
                        Textarea::make('description.ar')->label(__('Description (Arabic)'))->rows(3),
                    ]),
                Section::make(__('Pricing'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('price_per_kg')
                            ->label(__('Price per kg'))
                            ->numeric()
                            ->required()
                            ->default(0),
                        TextInput::make('price_per_piece')
                            ->label(__('Price per piece'))
                            ->numeric()
                            ->visible(fn ($get) => (bool) $get('sell_by_piece')),
                    ]),
                Section::make(__('Media'))
                    ->description(__('Upload a file or paste an image URL (upload is shown first on the shop).'))
                    ->schema([
                        FileUpload::make('image_path')
                            ->label(__('Product image (upload)'))
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->nullable()
                            ->columnSpanFull(),
                        TextInput::make('image_url')
                            ->label(__('Image URL (optional)'))
                            ->url()
                            ->maxLength(2048)
                            ->columnSpanFull(),
                    ]),
                Section::make(__('Analytics'))
                    ->description(__('Counts update when customers open this product on the shop or mobile API.'))
                    ->columns(2)
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->schema([
                        Placeholder::make('analytics_total_views')
                            ->label(__('Total page views'))
                            ->content(fn (?Product $record): string => $record ? number_format((int) $record->view_count) : '—'),
                        Placeholder::make('analytics_unique_visitors')
                            ->label(__('Unique visitors'))
                            ->content(function (?Product $record): string {
                                if (! $record) {
                                    return '—';
                                }

                                return number_format($record->unique_visitors_count ?? $record->productViews()->distinct('session_id')->count('session_id'));
                            }),
                        Placeholder::make('analytics_views_7d')
                            ->label(__('Views (last 7 days)'))
                            ->content(function (?Product $record): string {
                                if (! $record) {
                                    return '—';
                                }

                                $count = $record->product_views_7d ?? $record->productViews()->where('visited_at', '>=', now()->subDays(7))->count();

                                return number_format((int) $count);
                            }),
                        Placeholder::make('analytics_views_30d')
                            ->label(__('Views (last 30 days)'))
                            ->content(function (?Product $record): string {
                                if (! $record) {
                                    return '—';
                                }

                                $count = $record->product_views_30d ?? $record->productViews()->where('visited_at', '>=', now()->subDays(30))->count();

                                return number_format((int) $count);
                            }),
                        Placeholder::make('analytics_sold_kg')
                            ->label(__('Sold (kg)'))
                            ->content(function (?Product $record): string {
                                if (! $record) {
                                    return '—';
                                }

                                $sold = $record->sold_kg_total ?? $record->orderItems()->sum('quantity');

                                return number_format((float) $sold, 2);
                            }),
                    ]),
                Section::make(__('Special services (per product)'))
                    ->description(__('Toggle which preparation add-ons and packaging options apply to this product. Define catalog-wide services under “Preparation services” and “Packaging types”.'))
                    ->schema([
                        CheckboxList::make('preparationServices')
                            ->label(__('Preparation services'))
                            ->relationship(
                                name: 'preparationServices',
                                modifyQueryUsing: fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
                            )
                            ->getOptionLabelFromRecordUsing(function (PreparationService $r): string {
                                $suffix = $r->surcharge_is_percent
                                    ? (string) $r->surcharge_amount.'%'
                                    : (string) $r->surcharge_amount.' EGP';

                                return $r->getTranslation('name', 'en').' ('.$suffix.')';
                            })
                            ->columns(2)
                            ->gridDirection('row')
                            ->columnSpanFull(),
                        CheckboxList::make('packagingTypes')
                            ->label(__('Packaging types'))
                            ->relationship(
                                name: 'packagingTypes',
                                modifyQueryUsing: fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
                            )
                            ->getOptionLabelFromRecordUsing(function (PackagingType $r): string {
                                $suffix = $r->surcharge_is_percent
                                    ? (string) $r->surcharge_amount.'%'
                                    : (string) $r->surcharge_amount.' EGP';

                                return $r->getTranslation('name', 'en').' ('.$suffix.')';
                            })
                            ->columns(2)
                            ->gridDirection('row')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
