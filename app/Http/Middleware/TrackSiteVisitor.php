<?php

namespace App\Http\Middleware;

use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Services\Analytics\VisitorGeoLocator;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

final class TrackSiteVisitor
{
    private static ?bool $geoColumnsReady = null;

    public function __construct(private readonly VisitorGeoLocator $geo) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! Schema::hasTable('site_visitors')) {
            return $next($request);
        }

        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $sessionId = session()->getId();
        $now = now();
        $path = substr($request->path(), 0, 500);
        $ipHash = hash('sha256', (string) $request->ip().config('app.key'));
        $uaHash = hash('sha256', substr((string) $request->userAgent(), 0, 512));

        $visitor = SiteVisitor::query()->firstOrNew(['session_id' => $sessionId]);
        $isNew = ! $visitor->exists;
        $previousPath = $visitor->exists ? $visitor->last_path : null;

        if ($isNew) {
            $visitor->first_seen_at = $now;
            $ref = (string) $request->headers->get('Referer', '');
            $visitor->first_path = $path;
            $visitor->referrer = $ref !== '' ? substr($ref, 0, 2000) : null;
            $visitor->utm_source = $this->queryTrim($request, 'utm_source', 128);
            $visitor->utm_medium = $this->queryTrim($request, 'utm_medium', 128);
            $visitor->utm_campaign = $this->queryTrim($request, 'utm_campaign', 128);
        }

        $visitor->user_id = auth()->id();
        $visitor->last_path = $path;
        $visitor->ip_hash = $ipHash;
        $visitor->user_agent_hash = $uaHash;
        $visitor->device_type = self::guessDevice((string) $request->userAgent());
        $visitor->last_seen_at = $now;
        $visitor->save();

        $this->resolveLocation($visitor, $request);

        if (Schema::hasTable('site_page_views') && ($isNew || $previousPath !== $path)) {
            $pvRef = (string) $request->headers->get('Referer', '');
            SitePageView::query()->create([
                'session_id' => $sessionId,
                'path' => $path,
                'referrer' => $pvRef !== '' ? substr($pvRef, 0, 1000) : null,
                'visited_at' => $now,
            ]);
        }

        return $next($request);
    }

    private function resolveLocation(SiteVisitor $visitor, Request $request): void
    {
        self::$geoColumnsReady ??= Schema::hasColumn('site_visitors', 'geo_resolved_at');

        if (! self::$geoColumnsReady || $visitor->geo_resolved_at !== null) {
            return;
        }

        $fromHeaders = $this->geo->fromHeaders($request);
        if ($fromHeaders !== null) {
            $visitor->forceFill($fromHeaders + ['geo_resolved_at' => now()])->save();

            return;
        }

        $visitorId = $visitor->getKey();
        $ip = (string) $request->ip();

        // Runs after the response is sent so the HTTP lookup never slows the page.
        app()->terminating(function () use ($visitorId, $ip): void {
            $location = $this->geo->locate($ip);
            if ($location === null) {
                return;
            }

            SiteVisitor::query()
                ->whereKey($visitorId)
                ->whereNull('geo_resolved_at')
                ->update($location + ['geo_resolved_at' => now()]);
        });
    }

    private function queryTrim(Request $request, string $key, int $max): ?string
    {
        $v = $request->query($key);
        if (! is_string($v) || trim($v) === '') {
            return null;
        }

        return substr(trim($v), 0, $max);
    }

    private static function guessDevice(string $ua): string
    {
        $u = strtolower($ua);
        if (str_contains($u, 'tablet') || str_contains($u, 'ipad')) {
            return 'tablet';
        }
        if (str_contains($u, 'mobile') || str_contains($u, 'android') || str_contains($u, 'iphone')) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function shouldSkip(Request $request): bool
    {
        if (! $request->isMethod('GET')) {
            return true;
        }

        if ($request->expectsJson()) {
            return true;
        }

        $path = $request->path();
        if (str_starts_with($path, 'admin') || str_starts_with($path, 'my')) {
            return true;
        }

        if (str_starts_with($path, 'livewire')) {
            return true;
        }

        if ($path === 'up') {
            return true;
        }

        return false;
    }
}
