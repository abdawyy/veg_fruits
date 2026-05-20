<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('aldawy.require_email_verification')) {
            return $next($request);
        }

        $user = $request->user();
        if ($user === null || $user->hasVerifiedEmail()) {
            return $next($request);
        }

        return redirect()->route('verification.notice');
    }
}
