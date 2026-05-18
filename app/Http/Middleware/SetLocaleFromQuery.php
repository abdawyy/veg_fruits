<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocaleFromQuery
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('locale');
        if (is_string($locale) && in_array($locale, ['en', 'ar'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
