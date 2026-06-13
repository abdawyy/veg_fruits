<?php

namespace App\Filament\Resources\Products\Tables;

use App\Exports\ProductsExport;
use App\Models\Product;
use App\Support\Pdf\PdfRenderer;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
