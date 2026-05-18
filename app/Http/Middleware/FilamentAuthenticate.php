<?php

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticateMiddleware;

final class FilamentAuthenticate extends FilamentAuthenticateMiddleware
{
    protected function redirectTo($request): ?string
    {
        $panel = Filament::getCurrentPanel();
        if ($panel && $panel->getId() === 'account') {
            return route('login', ['redirect' => $request->fullUrl()]);
        }

        return parent::redirectTo($request);
    }
}
