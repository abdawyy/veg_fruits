<?php

namespace App\Filament\Resources\ProduceBoxes\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
                        TextInput::make('price')->label(__('Box price'))->numeric()->required()->default(0),
                        Toggle::make('is_active')->default(true),
                    ]),
                Section::make(__('Contents'))
                    ->description(__('Products included in this box (used for subscriptions and itemized orders).'))
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label(__('Product'))
                                    ->relationship('product', null, fn ($q) => $q->where('is_active', true)->orderBy('sku'))
                                    ->getOptionLabelFromRecordUsing(fn (Product $p) => $p->sku.' — '.$p->getTranslation('name', 'en'))
                                    ->searchable()
                                    ->required(),
                                TextInput::make('quantity')->numeric()->required()->default(1),
                                Select::make('unit')
                                    ->options(['kg' => 'kg', 'piece' => __('piece')])
                                    ->default('kg')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
