<?php

namespace App\Filament\Account\Resources\MyOrders\Pages;

use App\Filament\Account\Resources\MyOrders\MyOrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;

class ViewMyOrder extends ViewRecord
{
    protected static string $resource = MyOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadInvoice')
                ->label(__('aldawy.download_invoice'))
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->url(fn (Order $record): string => route('account.orders.invoice', $record))
                ->openUrlInNewTab()
                ->visible(fn (Order $record): bool => $record->invoice_path !== null
                    && Storage::disk('local')->exists($record->invoice_path)),
        ];
    }
}
