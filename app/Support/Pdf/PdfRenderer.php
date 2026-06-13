<?php

namespace App\Support\Pdf;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PdfRenderer
{
    public function __construct(
        private readonly ViewFactory $view,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function render(string $view, array $data = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $previousLocale = app()->getLocale();

        if ($locale !== $previousLocale) {
            app()->setLocale($locale);
        }

        try {
            $html = $this->view->make($view, $data)->render();
        } finally {
            if ($locale !== $previousLocale) {
                app()->setLocale($previousLocale);
            }
        }

        $html = $this->sanitizeUtf8($html);

        $mpdf = $this->makeMpdf($locale);
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function downloadResponse(string $view, array $data, string $filename, ?string $locale = null): StreamedResponse
    {
        $binary = $this->render($view, $data, $locale);

        return response()->streamDownload(
            static function () use ($binary): void {
                echo $binary;
            },
            $filename,
            [
                'Content-Type' => 'application/pdf',
            ],
        );
    }

    private function makeMpdf(string $locale): Mpdf
    {
        $tempDir = config('pdf.temp_dir', storage_path('app/mpdf'));

        if (! File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $margins = config('pdf.margins', []);

        return new Mpdf([
            'mode' => 'utf-8',
            'format' => config('pdf.format', 'A4'),
            'tempDir' => $tempDir,
            'margin_left' => $margins['left'] ?? 12,
            'margin_right' => $margins['right'] ?? 12,
            'margin_top' => $margins['top'] ?? 12,
            'margin_bottom' => $margins['bottom'] ?? 12,
            'default_font' => config('pdf.default_font', 'dejavusans'),
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'directionality' => $locale === 'ar' ? 'rtl' : 'ltr',
        ]);
    }

    private function sanitizeUtf8(string $html): string
    {
        if (mb_check_encoding($html, 'UTF-8')) {
            return $html;
        }

        return mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    }
}
