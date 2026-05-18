<?php

namespace App\Actions\Invoices;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

final class GenerateInvoicePdfAction
{
    public function execute(Order $order): string
    {
        $order->load(['items', 'city']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
            'senderName' => config('aldawy.invoice_sender_name'),
        ])->setPaper('a4');

        $relativePath = 'invoices/'.$order->reference.'.pdf';
        Storage::disk('local')->put($relativePath, $pdf->output());

        $order->forceFill(['invoice_path' => $relativePath])->save();

        return $relativePath;
    }
}
