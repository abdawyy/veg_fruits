<?php

namespace App\Filament\Resources\SiteVisitors\Tables;

use App\Models\SiteVisitor;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;

class SiteVisitorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultSort('last_seen_at', 'desc')
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('session_id')->label(__('Session'))->limit(20)->toggleable(),
                TextColumn::make('user.email')->label(__('User'))->placeholder('—'),
                TextColumn::make('country')
                    ->label(__('Country'))
                    ->placeholder('—')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('city')
                    ->label(__('City'))
                    ->description(fn (SiteVisitor $record): ?string => $record->region)
                    ->placeholder('—')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('first_path')->label(__('Entry path'))->limit(36)->tooltip(fn ($state) => $state)->toggleable(),
                TextColumn::make('last_path')->label(__('Last path'))->limit(40)->tooltip(fn ($state) => $state),
                TextColumn::make('referrer')->label(__('Referrer'))->limit(32)->tooltip(fn ($state) => $state)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('utm_campaign')->label(__('UTM campaign'))->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('device_type')->label(__('Device'))->badge()->toggleable(),
                TextColumn::make('last_seen_at')->dateTime()->sortable(),
                TextColumn::make('first_seen_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('country')
                    ->label(__('Country'))
                    ->options(fn (): array => Schema::hasColumn('site_visitors', 'country')
                        ? SiteVisitor::query()->whereNotNull('country')->distinct()->orderBy('country')->pluck('country', 'country')->all()
                        : []),
                SelectFilter::make('device_type')
                    ->label(__('Device'))
                    ->options([
                        'mobile' => __('Mobile'),
                        'tablet' => __('Tablet'),
                        'desktop' => __('Desktop'),
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([
                DeleteBulkAction::make()->label(__('Purge selected')),
            ]);
    }
}
