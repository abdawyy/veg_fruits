<?php

namespace App\Actions\Invoices;

use App\Models\Order;
use App\Support\Pdf\PdfRenderer;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function ensure(Order $order): string
    {
        if ($this->hasFile($order)) {
            return (string) $order->invoice_path;
        }

        return $this->execute($order);
    }

    public function ensureAll(): int
    {
        $generated = 0;

        Order::query()->orderBy('id')->chunkById(50, function ($orders) use (&$generated): void {
            foreach ($orders as $order) {
                if ($this->hasFile($order)) {
                    continue;
                }

                $this->execute($order);
                $generated++;
            }
        });

        return $generated;
    }

    public function download(Order $order, bool $refresh = false): StreamedResponse
    {
        $path = $refresh ? $this->execute($order) : $this->ensure($order);
        $filename = 'invoice-'.str_replace(['/', '\\'], '-', $order->reference).'.pdf';

        return Storage::disk('local')->download($path, $filename);
    }

    private function hasFile(Order $order): bool
    {
        return is_string($order->invoice_path)
            && $order->invoice_path !== ''
            && Storage::disk('local')->exists($order->invoice_path);
    }
}
