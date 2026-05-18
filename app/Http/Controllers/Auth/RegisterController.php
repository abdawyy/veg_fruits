<?php

namespace App\Http\Controllers\Auth;

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
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
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

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('store.home')->with('status', __('aldawy.registered'));
    }
}
