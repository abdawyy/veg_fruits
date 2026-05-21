<?php

namespace App\Filament\Resources\Products\Pages;

use App\Exports\ProductsExport;
use App\Filament\Resources\Products\ProductResource;
use App\Imports\ProductsImport;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use App\Support\Imports\SpreadsheetImportRunner;
use Filament\Forms\Components\Toggle;
use Maatwebsite\Excel\Facades\Excel;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label(__('Export Excel'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(new ProductsExport, 'products-'.now()->format('Y-m-d').'.xlsx')),
            Action::make('export_pdf')
                ->label(__('Export PDF catalog'))
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    $products = Product::query()->active()->with('category')->orderBy('name->en')->get();

                    return Pdf::loadView('pdf.catalog', ['products' => $products])
                        ->download('aldawy-catalog-'.now()->format('Y-m-d').'.pdf');
                }),
            Action::make('import_excel')
                ->label(__('Import Excel'))
                ->icon('heroicon-o-arrow-up-tray')
                ->schema([
                    FileUpload::make('file')
                        ->label(__('Spreadsheet'))
                        ->disk('local')
                        ->directory('imports')
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                    Toggle::make('dry_run')
                        ->label(__('Dry run (validate only)'))
                        ->default(true),
                ])
                ->action(function (array $data): void {
                    $relative = $data['file'] ?? null;
                    if (! is_string($relative) || $relative === '') {
                        Notification::make()->title(__('Missing file'))->danger()->send();

                        return;
                    }
                    $path = Storage::disk('local')->path($relative);
                    $runner = app(SpreadsheetImportRunner::class);
                    $dryRun = (bool) ($data['dry_run'] ?? true);

                    if ($dryRun) {
                        $result = $runner->dryRun(ProductsImport::class, $path);
                        $runner->logDryRun(ProductsImport::class, $path, $result, auth()->id(), basename($relative));
                        Notification::make()
                            ->title(__('Dry run complete'))
                            ->body(__('OK: :ok — Failed: :failed', ['ok' => $result['ok'], 'failed' => $result['failed']]))
                            ->success()
                            ->send();

                        return;
                    }

                    $log = $runner->commit(ProductsImport::class, $path, auth()->id(), basename($relative));
                    Notification::make()
                        ->title(__('Import finished'))
                        ->body(__('OK: :ok — Failed: :failed', ['ok' => $log->rows_ok, 'failed' => $log->rows_failed]))
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
