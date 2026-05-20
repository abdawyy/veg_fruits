@extends('layouts.guest')

@section('title', __('aldawy.forgot_password_title'))

@section('content')
    <h1 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.forgot_password_title') }}</h1>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.forgot_password_sub') }}</p>

    @if (session('status'))
        <p class="mt-4 rounded-xl border border-brand/30 bg-brand/5 px-3 py-2 text-sm text-brand dark:bg-brand/10">{{ session('status') }}</p>
    @endif

    <form method="post" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('aldawy.email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                class="mt-1 w-full rounded-xl border border-slate-200 bg-canvas px-3 py-2 text-sm shadow-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
            @error('email')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="w-full rounded-xl bg-brand py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
            {{ __('aldawy.forgot_password_submit') }}
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
        <a href="{{ route('login') }}" class="font-semibold text-brand hover:text-brand-dark">{{ __('aldawy.back_to_login') }}</a>
    </p>
@endsection
