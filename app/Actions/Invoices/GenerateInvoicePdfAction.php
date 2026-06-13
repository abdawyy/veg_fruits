<?php

namespace App\Actions\Invoices;

use App\Models\Order;
use App\Support\Pdf\PdfRenderer;
use Illuminate\Support\Facades\Storage;

final class GenerateInvoicePdfAction
{
    public function execute(Order $order): string
    {
        $order->load(['items', 'city']);

        $pdfBinary = app(PdfRenderer::class)->render('pdf.invoice', [
            'order' => $order,
            'senderName' => config('aldawy.invoice_sender_name'),
        ]);

        $relativePath = 'invoices/'.$order->reference.'.pdf';
        Storage::disk('local')->put($relativePath, $pdfBinary);

        $order->forceFill(['invoice_path' => $relativePath])->save();

        return $relativePath;
    }
}
