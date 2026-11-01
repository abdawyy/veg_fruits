<?php

namespace App\Filament\Resources\SeoSettings\Tables;

use App\Filament\Support\TableSearch;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SeoSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->searchUsing(fn (Builder $query, string $search): Builder => TableSearch::apply($query, $search, [
                'home_meta_title_en',
                'home_meta_title_ar',
                'home_meta_description_en',
                'home_meta_description_ar',
                'shop_meta_title_en',
                'shop_meta_title_ar',
                'shop_meta_description_en',
                'shop_meta_description_ar',
                'services_meta_title_en',
                'services_meta_title_ar',
                'services_meta_description_en',
                'services_meta_description_ar',
                'product_meta_title_suffix_en',
                'product_meta_title_suffix_ar',
                'og_image_url',
            ]))
            ->columns([
                TextColumn::make('id')->label(__('ID')),
                TextColumn::make('updated_at')->dateTime()->label(__('Last updated')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
