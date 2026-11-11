<?php

namespace App\Filament\Resources\Products\Tables;

use App\Exports\ProductsExport;
use App\Filament\Support\TableSearch;
use App\Models\Product;
use App\Support\Pdf\PdfRenderer;
use App\Support\ProductBulkEditor;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->searchUsing(fn (Builder $query, string $search): Builder => TableSearch::apply($query, $search, [
                'sku',
                'name',
                'description',
                'category.name',
            ]))
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([10, 25, 50, 100])
            ->columns([
                ImageColumn::make('display_image_url')
                    ->label(__('Image'))
                    ->checkFileExistence(false)
                    ->square()
                    ->size(48),
                TextColumn::make('sku')->searchable()->sortable(),
                TextColumn::make('name')
                    ->label(__('Name (EN)'))
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['en'] ?? '') : (string) $state),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->formatStateUsing(fn ($state, $record) => $record->category?->getTranslation('name', 'en') ?? '—'),
                TextColumn::make('price_per_kg')->sortable()->numeric(decimalPlaces: 2),
                TextColumn::make('min_order_quantity')
                    ->label(__('Min qty'))
                    ->placeholder('—')
                    ->numeric(decimalPlaces: 2)
                    ->toggleable(),
                TextColumn::make('sold_kg_total')
                    ->label(__('Sold (kg)'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('view_count')
                    ->label(__('Total views'))
                    ->sortable()
                    ->numeric()
                    ->description(__('Product detail page loads')),
                TextColumn::make('unique_visitors_count')
                    ->label(__('Unique visitors'))
                    ->numeric()
                    ->sortable()
                    ->default(0),
                TextColumn::make('product_views_7d')
                    ->label(__('Views (7d)'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('product_views_30d')
                    ->label(__('Views (30d)'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('stock_quantity')
                    ->label(__('Stock'))
                    ->placeholder('—')
                    ->toggleable(),
                IconColumn::make('sell_by_piece')
                    ->label(__('Piece'))
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('apply_price')
                        ->label(__('Apply price'))
                        ->icon('heroicon-o-currency-dollar')
                        ->schema([
                            TextInput::make('price_per_kg')
                                ->label(__('Price per kg'))
                                ->numeric()
                                ->minValue(0),
                            TextInput::make('price_per_piece')
                                ->label(__('Price per piece'))
                                ->numeric()
                                ->minValue(0)
                                ->helperText(__('Leave empty to keep each product’s piece price.')),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $count = ProductBulkEditor::applyPrice(
                                $records,
                                isset($data['price_per_kg']) ? (string) $data['price_per_kg'] : null,
                                isset($data['price_per_piece']) ? (string) $data['price_per_piece'] : null,
                            );
                            Notification::make()
                                ->title(__('Updated :count products', ['count' => $count]))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('apply_discount')
                        ->label(__('Apply discount'))
                        ->icon('heroicon-o-receipt-percent')
                        ->schema([
                            Select::make('mode')
                                ->label(__('Discount type'))
                                ->options([
                                    'percent' => __('Percent off'),
                                    'fixed' => __('Fixed amount off'),
                                ])
                                ->required()
                                ->default('percent'),
                            TextInput::make('value')
                                ->label(__('Amount'))
                                ->numeric()
                                ->required()
                                ->minValue(0.01)
                                ->helperText(__('Percent: 10 = 10% off. Fixed: EGP off the price.')),
                            Select::make('target')
                                ->label(__('Apply to'))
                                ->options([
                                    'both' => __('Kg and piece prices'),
                                    'kg' => __('Kg price only'),
                                    'piece' => __('Piece price only'),
                                ])
                                ->required()
                                ->default('both'),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $count = ProductBulkEditor::applyDiscount(
                                $records,
                                (string) $data['mode'],
                                (string) $data['value'],
                                (string) ($data['target'] ?? 'both'),
                            );
                            Notification::make()
                                ->title(__('Discounted :count products', ['count' => $count]))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('mark_inactive')
                        ->label(__('Mark inactive'))
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $count = ProductBulkEditor::setActive($records, false);
                            Notification::make()
                                ->title(__('Marked :count products inactive', ['count' => $count]))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('mark_active')
                        ->label(__('Mark active'))
                        ->icon('heroicon-o-eye')
                        ->action(function (Collection $records): void {
                            $count = ProductBulkEditor::setActive($records, true);
                            Notification::make()
                                ->title(__('Marked :count products active', ['count' => $count]))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('set_min_order_quantity')
                        ->label(__('Change minimum order qty'))
                        ->icon('heroicon-o-scale')
                        ->schema([
                            TextInput::make('min_order_quantity')
                                ->label(__('Minimum order quantity'))
                                ->numeric()
                                ->minValue(0)
                                ->helperText(__('Empty clears the custom minimum and uses the shop default.')),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $raw = $data['min_order_quantity'] ?? null;
                            $count = ProductBulkEditor::setMinOrderQuantity(
                                $records,
                                $raw === null || $raw === '' ? null : (string) $raw,
                            );
                            Notification::make()
                                ->title(__('Updated minimum qty on :count products', ['count' => $count]))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('set_sell_unit')
                        ->label(__('Sell by kg or piece'))
                        ->icon('heroicon-o-shopping-bag')
                        ->schema([
                            Select::make('mode')
                                ->label(__('Selling unit'))
                                ->options([
                                    'kg' => __('Kilogram only'),
                                    'piece' => __('Allow piece (and kg)'),
                                ])
                                ->required()
                                ->default('kg'),
                            TextInput::make('price_per_piece')
                                ->label(__('Price per piece'))
                                ->numeric()
                                ->minValue(0)
                                ->helperText(__('Used when enabling piece sales and a product has no piece price yet.')),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $count = ProductBulkEditor::setSellUnit(
                                $records,
                                (string) $data['mode'],
                                isset($data['price_per_piece']) ? (string) $data['price_per_piece'] : null,
                            );
                            Notification::make()
                                ->title(__('Updated sell unit on :count products', ['count' => $count]))
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('export_excel')
                        ->label(__('Export Excel'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->action(function (Collection $records) {
                            $ids = $records->pluck('id')->all();

                            return Excel::download(
                                new ProductsExport($ids),
                                'products-selected-'.now()->format('Y-m-d').'.xlsx',
                            );
                        }),
                    BulkAction::make('export_pdf')
                        ->label(__('Export PDF'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('gray')
                        ->action(function (Collection $records) {
                            $products = Product::query()
                                ->whereIn('id', $records->pluck('id'))
                                ->with('category')
                                ->orderBy('name->en')
                                ->get();

                            return app(PdfRenderer::class)->downloadResponse(
                                'pdf.catalog',
                                ['products' => $products],
                                'products-selected-'.now()->format('Y-m-d').'.pdf',
                            );
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
