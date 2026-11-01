<?php

namespace App\Filament\Resources\ContentStrings\Tables;

use App\Filament\Support\TableSearch;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContentStringsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->searchUsing(fn (Builder $query, string $search): Builder => TableSearch::apply($query, $search, [
                'key',
                'group',
                'value_en',
                'value_ar',
                'admin_note',
            ]))
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([10, 25, 50, 100])
            ->columns([
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('group')->badge()->sortable(),
                TextColumn::make('value_en')->label(__('EN'))->limit(40)->toggleable(),
                TextColumn::make('value_ar')->label(__('AR'))->limit(40)->toggleable(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
