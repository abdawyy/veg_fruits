<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Orders\LinkGuestOrdersToUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class LoginController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.login', [
            'redirect' => $request->string('redirect')->toString(),
        ]);
    }

    public function store(Request $request, LinkGuestOrdersToUserAction $linkGuestOrders): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $linkGuestOrders->execute($request->user(), $request->session()->pull('link_guest_order_id'));

        $intended = $request->input('redirect');
        if (is_string($intended) && $intended !== '') {
            if (str_starts_with($intended, '/') && ! str_starts_with($intended, '//')) {
                return redirect()->to($intended);
            }
            $host = parse_url($intended, PHP_URL_HOST);
            if ($host && $host === $request->getHost()) {
                return redirect()->to($intended);
            }
        }

        return redirect()->intended(route('store.home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('store.home');
    }
}
