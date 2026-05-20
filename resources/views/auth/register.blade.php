@extends('layouts.guest')

@section('title', __('aldawy.register_title'))

@section('content')
    <h1 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.register_title') }}</h1>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.register_sub') }}</p>

    <form method="post" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf
        @if (! empty($redirect))
            <input type="hidden" name="redirect" value="{{ $redirect }}">
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.name') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            @error('name')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            @error('email')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.phone_optional') }}</label>
            <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}" autocomplete="tel"
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            @error('phone_number')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.password') }}</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            @error('password')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.password_confirm') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
        </div>

        <button type="submit" class="w-full rounded-xl bg-brand py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
            {{ __('aldawy.register_submit') }}
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        {{ __('aldawy.have_account') }}
        <a href="{{ route('login', ! empty($redirect) ? ['redirect' => $redirect] : []) }}" class="font-semibold text-brand hover:text-brand-dark">{{ __('aldawy.nav_login') }}</a>
    </p>
@endsection
