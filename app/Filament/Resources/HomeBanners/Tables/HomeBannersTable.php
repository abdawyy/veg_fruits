<?php

namespace App\Filament\Resources\HomeBanners\Tables;

use App\Models\HomeBanner;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomeBannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')->sortable(),
                TextColumn::make('title.en')->label('Title (EN)')->searchable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('starts_at')->dateTime()->toggleable(),
                TextColumn::make('ends_at')->dateTime()->toggleable(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ReplicateAction::make()
                    ->excludeAttributes(['image_path'])
                    ->beforeReplicaSaved(function ($replica): void {
                        $replica->sort_order = (int) (HomeBanner::query()->max('sort_order') ?? 0) + 1;
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
