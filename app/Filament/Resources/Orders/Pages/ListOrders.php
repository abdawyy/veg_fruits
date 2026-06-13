<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Exports\OrdersExport;
use App\Filament\Resources\Orders\OrderResource;
use App\Imports\OrdersImport;
use App\Models\Order;
use App\Support\Pdf\PdfRenderer;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label(__('Export Excel'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(new OrdersExport, 'orders-'.now()->format('Y-m-d').'.xlsx')),
            Action::make('import_excel')
                ->label(__('Import Excel'))
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->modalDescription(__('Match rows by order reference. Updates status and notes only.'))
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
                ])
                ->action(function (array $data): void {
                    $relative = $data['file'] ?? null;
                    if (! is_string($relative) || $relative === '') {
                        Notification::make()->title(__('Missing file'))->danger()->send();

                        return;
                    }
                    $path = Storage::disk('local')->path($relative);
                    Excel::import(new OrdersImport, $path);
                    Notification::make()->title(__('Import finished'))->success()->send();
                }),
            Action::make('export_pdf')
                ->label(__('Export PDF summary'))
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    $orders = Order::query()
                        ->with('user')
                        ->orderByDesc('id')
                        ->limit(500)
                        ->get();

                    return app(PdfRenderer::class)->downloadResponse(
                        'pdf.orders-summary',
                        ['orders' => $orders],
                        'orders-summary-'.now()->format('Y-m-d').'.pdf',
                    );
                }),
        ];
    }
}
