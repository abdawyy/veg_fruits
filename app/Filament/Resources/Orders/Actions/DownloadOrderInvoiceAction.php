<?php

namespace App\Filament\Resources\Orders\Actions;

use App\Actions\Invoices\GenerateInvoicePdfAction;
use App\Models\Order;
use Filament\Actions\Action;

final class DownloadOrderInvoiceAction
{
    public static function make(): Action
    {
        return Action::make('downloadInvoice')
            ->label(__('aldawy.download_invoice'))
            ->icon('heroicon-o-document-arrow-down')
            ->action(fn (Order $record) => app(GenerateInvoicePdfAction::class)->download($record, refresh: true));
    }
}
