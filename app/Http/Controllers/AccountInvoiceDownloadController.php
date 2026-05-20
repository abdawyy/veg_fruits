<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class AccountInvoiceDownloadController
{
    public function __invoke(Request $request, Order $order): StreamedResponse
    {
        abort_unless($request->user() !== null, 403);
        abort_unless((int) $order->user_id === (int) $request->user()->id, 403);

        if (! $order->invoice_path || ! Storage::disk('local')->exists($order->invoice_path)) {
            abort(404);
        }

        return Storage::disk('local')->download($order->invoice_path, 'invoice-'.$order->reference.'.pdf');
    }
}
