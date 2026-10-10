<?php

namespace App\Services\Analytics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Locale;
use Throwable;

final class VisitorGeoLocator
{
    public const LOCAL_NETWORK = 'Local network';

    /**
     * Location from CDN headers (Cloudflare), available without any lookup.
     *
     * @return array{country_code: ?string, country: ?string, region: ?string, city: ?string}|null
     */
    public function fromHeaders(Request $request): ?array
    {
        $code = strtoupper(trim((string) $request->headers->get('CF-IPCountry', '')));

        if (strlen($code) !== 2 || in_array($code, ['XX', 'T1'], true)) {
            return null;
        }

        return [
            'country_code' => $code,
            'country' => self::countryName($code),
            'region' => self::clean($request->headers->get('cf-region')),
            'city' => self::clean($request->headers->get('cf-ipcity')),
        ];
    }

    /**
     * Location for an IP via the configured HTTP service. Null when unavailable (retry later).
     *
     * @return array{country_code: ?string, country: ?string, region: ?string, city: ?string}|null
     */
    public function locate(string $ip): ?array
    {
        if (! self::isPublicIp($ip)) {
            return [
                'country_code' => null,
                'country' => self::LOCAL_NETWORK,
                'region' => null,
                'city' => null,
            ];
        }

        if (! config('analytics.geoip.enabled') || blank(config('analytics.geoip.url'))) {
            return null;
        }

        $key = 'geoip:'.hash('sha256', $ip.config('app.key'));
        $failKey = $key.':failed';

        if (Cache::has($failKey)) {
            return null;
        }

        $cached = Cache::get($key);
        if (is_array($cached)) {
            return $cached;
        }

        $location = $this->fetch($ip);

        if ($location === null) {
            Cache::put($failKey, true, now()->addHour());

            return null;
        }

        Cache::put($key, $location, now()->addDays(max(1, (int) config('analytics.geoip.cache_days', 7))));

        return $location;
    }

    public static function isPublicIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
    }

    /**
     * @return array{country_code: ?string, country: ?string, region: ?string, city: ?string}|null
     */
    private function fetch(string $ip): ?array
    {
        $url = str_replace('{ip}', rawurlencode($ip), (string) config('analytics.geoip.url'));

        try {
            $response = Http::timeout((float) config('analytics.geoip.timeout', 2))
                ->acceptJson()
                ->get($url);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        if (! is_array($data)) {
            return null;
        }

        // ipwho.is: success=false, ip-api.com: status=fail, ipapi.co: error=true
        if (($data['success'] ?? true) === false || ($data['status'] ?? 'success') !== 'success' || ($data['error'] ?? false) === true) {
            return null;
        }

        $code = strtoupper((string) ($data['country_code'] ?? $data['countryCode'] ?? ''));
        $code = strlen($code) === 2 ? $code : null;

        $country = self::clean($data['country_name'] ?? null);
        if ($country === null) {
            $raw = self::clean($data['country'] ?? null);
            $country = $raw !== null && strlen($raw) > 2 ? $raw : null;
        }
        $country ??= $code !== null ? self::countryName($code) : null;

        if ($code === null && $country === null) {
            return null;
        }

        return [
            'country_code' => $code,
            'country' => $country,
            'region' => self::clean($data['region'] ?? $data['regionName'] ?? null),
            'city' => self::clean($data['city'] ?? null),
        ];
    }

    private static function countryName(string $code): string
    {
        if (class_exists(Locale::class)) {
            $name = Locale::getDisplayRegion('und_'.$code, 'en');
            if (is_string($name) && $name !== '' && strtoupper($name) !== $code) {
                return $name;
            }
        }

        return $code;
    }

    private static function clean(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim(rawurldecode($value));

        return $value === '' ? null : mb_substr($value, 0, 100);
    }
}
