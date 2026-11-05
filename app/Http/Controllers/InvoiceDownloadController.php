<?php

namespace App\Http\Controllers;

use App\Actions\Invoices\AuthorizeInvoiceDownloadAction;
use App\Actions\Invoices\GenerateInvoicePdfAction;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class InvoiceDownloadController
{
    public function __invoke(
        Request $request,
        Order $order,
        AuthorizeInvoiceDownloadAction $authorize,
    ): StreamedResponse {
        $authorize->assertCanDownload($request, $order);

        return app(GenerateInvoicePdfAction::class)->download($order);
    }
}
