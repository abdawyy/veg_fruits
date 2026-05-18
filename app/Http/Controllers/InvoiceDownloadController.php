<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class InvoiceDownloadController
{
    public function __invoke(Request $request, Order $order): StreamedResponse
    {
        if (! $order->invoice_path || ! Storage::disk('local')->exists($order->invoice_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($order->invoice_path, 'invoice-'.$order->reference.'.pdf');
    }
}
