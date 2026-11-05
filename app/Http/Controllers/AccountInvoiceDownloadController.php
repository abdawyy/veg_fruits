<?php

namespace App\Http\Controllers;

use App\Actions\Invoices\GenerateInvoicePdfAction;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class AccountInvoiceDownloadController
{
    public function __invoke(Request $request, Order $order): StreamedResponse
    {
        abort_unless($request->user() !== null, 403);
        abort_unless((int) $order->user_id === (int) $request->user()->id, 403);

        return app(GenerateInvoicePdfAction::class)->download($order);
    }
}
