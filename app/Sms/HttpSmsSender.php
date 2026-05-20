<?php

namespace App\Sms;

use App\Contracts\Sms\SmsSenderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Generic HTTP SMS gateway — configure URL and field names in config/aldawy.php.
 */
final class HttpSmsSender implements SmsSenderInterface
{
    public function send(string $phoneE164, string $message): void
    {
        $url = config('aldawy.sms.http.url');
        if (! is_string($url) || $url === '') {
            Log::warning('[SMS] HTTP driver selected but ALDAWY_SMS_HTTP_URL is empty.', [
                'phone' => $phoneE164,
            ]);

            return;
        }

        $phoneKey = (string) config('aldawy.sms.http.phone_key', 'to');
        $messageKey = (string) config('aldawy.sms.http.message_key', 'message');
        $payload = [
            $phoneKey => $phoneE164,
            $messageKey => $message,
        ];

        $request = Http::timeout(15);
        $apiKey = config('aldawy.sms.http.api_key');
        if (is_string($apiKey) && $apiKey !== '') {
            $header = (string) config('aldawy.sms.http.api_key_header', 'Authorization');
            $request = $request->withHeaders([$header => $apiKey]);
        }

        $method = strtolower((string) config('aldawy.sms.http.method', 'post'));
        $response = match ($method) {
            'get' => $request->get($url, $payload),
            'put' => $request->put($url, $payload),
            default => $request->post($url, $payload),
        };

        if (! $response->successful()) {
            Log::error('[SMS] Gateway request failed.', [
                'phone' => $phoneE164,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('SMS gateway request failed with status '.$response->status());
        }
    }
}
