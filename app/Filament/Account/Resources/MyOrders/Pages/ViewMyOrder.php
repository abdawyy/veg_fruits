<?php

namespace App\Filament\Account\Resources\MyOrders\Pages;

use App\Actions\Orders\CancelCustomerOrderAction;
use App\Filament\Account\Resources\MyOrders\MyOrderResource;
use App\Filament\Resources\Pages\ViewRecord;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;

class ViewMyOrder extends ViewRecord
{
    protected static string $resource = MyOrderResource::class;

    /**
     * @return array{0: string, 1: 'asc'|'desc'}
     */
    protected function getRecordNavigationSort(): array
    {
        return ['created_at', 'desc'];
    }

    protected function getResourceHeaderActions(): array
    {
        return [
            Action::make('cancelOrder')
                ->label(__('aldawy.order_cancel_action'))
                ->icon(Heroicon::OutlinedXCircle)
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (Order $record): bool => $record->canCustomerCancel())
                ->action(function (Order $record, CancelCustomerOrderAction $cancel): void {
                    $cancel->execute($record, auth()->user());
                })
                ->successNotificationTitle(__('aldawy.order_cancelled')),
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
