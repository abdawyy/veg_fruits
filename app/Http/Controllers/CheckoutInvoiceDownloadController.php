<?php

namespace App\Http\Controllers;

use App\Actions\Invoices\AuthorizeInvoiceDownloadAction;
use App\Actions\Invoices\GenerateInvoicePdfAction;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Download invoice for the order in the current checkout session (thanks page). */
final class CheckoutInvoiceDownloadController
{
    public function __invoke(Request $request, AuthorizeInvoiceDownloadAction $authorize): StreamedResponse
    {
        $orderId = $request->session()->get('checkout_order_id');
        if (! is_numeric($orderId)) {
            abort(404);
        }

        $order = Order::query()->findOrFail((int) $orderId);

        if ((int) $order->id !== (int) $orderId) {
            abort(403);
        }

        if ($request->user()) {
            $authorize->assertCanDownload($request, $order);
        }

        return app(GenerateInvoicePdfAction::class)->download($order);
    }
}
