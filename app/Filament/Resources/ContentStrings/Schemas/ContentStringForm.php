<?php

namespace App\Filament\Resources\ContentStrings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContentStringForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Key'))
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->maxLength(191)
                            ->unique(ignoreRecord: true)
                            ->helperText(__('Use dot notation, e.g. home.hero_kicker')),
                        Select::make('group')
                            ->options([
                                'general' => __('General'),
                                'home' => __('Home'),
                                'shop' => __('Shop'),
                                'footer' => __('Footer'),
                            ])
                            ->default('general')
                            ->required(),
                        Textarea::make('admin_note')->rows(2)->columnSpanFull(),
                    ]),
                Section::make(__('Copy'))
                    ->columns(1)
                    ->schema([
                        Textarea::make('value_en')->label(__('English'))->rows(4)->columnSpanFull(),
                        Textarea::make('value_ar')->label(__('Arabic'))->rows(4)->columnSpanFull(),
                    ]),
            ]);
    }
}
