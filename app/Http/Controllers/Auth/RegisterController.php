<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Orders\LinkGuestOrdersToUserAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

final class RegisterController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.register', [
            'redirect' => $request->string('redirect')->toString(),
        ]);
    }

    public function store(Request $request, LinkGuestOrdersToUserAction $linkGuestOrders): RedirectResponse
    {
        if ($request->input('phone_number') === '' || $request->input('phone_number') === null) {
            $request->merge(['phone_number' => null]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:32', 'unique:users,phone_number'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $phone = isset($data['phone_number']) ? trim((string) $data['phone_number']) : '';
        if ($phone === '') {
            $phone = null;
        }

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $phone,
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        event(new Registered($user));

        if (config('aldawy.require_email_verification')) {
            $user->sendEmailVerificationNotification();
        }

        Auth::login($user);
        $request->session()->regenerate();

        $linked = $linkGuestOrders->execute($user, $request->session()->pull('link_guest_order_id'));
        $request->session()->forget('checkout_order_id');

        if (config('aldawy.require_email_verification') && ! $user->hasVerifiedEmail()) {
            $status = $linked > 0
                ? __('aldawy.registered_orders_linked', ['count' => $linked])
                : __('aldawy.registered');

            return redirect()->route('verification.notice')->with('status', $status);
        }

        $status = $linked > 0
            ? __('aldawy.registered_orders_linked', ['count' => $linked])
            : __('aldawy.registered');

        return $this->redirectAfterAuth($request)->with('status', $status);
    }

    private function redirectAfterAuth(Request $request): RedirectResponse
    {
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

        return redirect()->route('store.home');
    }
}
