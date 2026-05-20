@extends('layouts.guest')

@section('title', __('aldawy.reset_password_title'))

@section('content')
    <h1 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.reset_password_title') }}</h1>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.reset_password_sub') }}</p>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="username"
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            @error('email')
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
            {{ __('aldawy.reset_password_submit') }}
        </button>
    </form>
@endsection
