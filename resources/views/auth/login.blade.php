@extends('layouts.guest')

@section('title', __('aldawy.login_title'))

@section('content')
    <h1 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.login_title') }}</h1>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.login_sub') }}</p>

    <form method="post" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf
        @if(request()->filled('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        @endif

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            @error('email')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between gap-2">
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.password') }}</label>
                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">{{ __('aldawy.forgot_password_link') }}</a>
            </div>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
            <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-brand focus:ring-brand">
            {{ __('aldawy.remember_me') }}
        </label>

        <button type="submit" class="w-full rounded-xl bg-brand py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
            {{ __('aldawy.nav_login') }}
        </button>
    </form>

    @if (config('aldawy.otp.enabled'))
        <p class="mt-4 text-center text-sm">
            <a href="{{ route('phone.login', request()->only('redirect')) }}" class="font-semibold text-brand hover:text-brand-dark">{{ __('aldawy.phone_login_link') }}</a>
        </p>
    @endif
    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        {{ __('aldawy.no_account') }}
        <a href="{{ route('register') }}" class="font-semibold text-brand hover:text-brand-dark">{{ __('aldawy.nav_register') }}</a>
    </p>
@endsection
