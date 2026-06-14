<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class ProductImageFetcher
{
    private const DIRECTORY = 'products';

    /**
     * Download a stock photo for the product and store it on the public disk.
     * Returns the relative storage path (e.g. products/apple.jpg) or null on failure.
     *
     * @param  list<string>|null  $sourceUrls
     */
    public static function fetchForProduct(Product $product, ?array $sourceUrls = null): ?string
    {
        $englishName = $product->getTranslation('name', 'en');
        $urls = $sourceUrls ?? ProduceStockPhoto::candidateUrlsFor($englishName, $product->sku);

        foreach ($urls as $url) {
            if ($url === '') {
                continue;
            }

            $response = self::download($url);

            if ($response === null || ! $response->successful()) {
                usleep(350_000);

                continue;
            }

            $body = $response->body();
            if ($body === '') {
                continue;
            }

            $extension = self::guessExtension($response->header('Content-Type'), $url);
            $filename = Str::slug($englishName).'.'.$extension;
            $path = self::DIRECTORY.'/'.$filename;

            Storage::disk('public')->put($path, $body);

            return $path;
        }

        return null;
    }

    private static function download(string $url): ?Response
    {
        $headers = ['User-Agent' => 'AL-DAWY-ProductImageFetcher/1.0 (produce catalog)'];
        $request = Http::timeout(45)->withHeaders($headers);

        try {
            $response = $request->get($url);

            if ($response->successful()) {
                return $response;
            }
        } catch (\Throwable) {
            // Retry below when local SSL bundle is misconfigured (common on Laragon).
        }

        if (! App::environment('local')) {
            return null;
        }

        try {
            return Http::timeout(45)
                ->withOptions(['verify' => false])
                ->withHeaders($headers)
                ->get($url);
        } catch (\Throwable) {
            return null;
        }
    }

    private static function guessExtension(?string $contentType, string $url): string
    {
        return match (true) {
            str_contains((string) $contentType, 'png') => 'png',
            str_contains((string) $contentType, 'webp') => 'webp',
            str_contains((string) $contentType, 'gif') => 'gif',
            str_contains($url, '.png') => 'png',
            str_contains($url, '.webp') => 'webp',
            default => 'jpg',
        };
    }
}
