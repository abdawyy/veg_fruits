@extends('layouts.guest')

@section('title', __('aldawy.verify_email_title'))

@section('content')
    <h1 class="text-xl font-bold text-slate-800 dark:text-white">{{ __('aldawy.verify_email_title') }}</h1>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('aldawy.verify_email_sub') }}</p>

    @if (session('status'))
        <p class="mt-4 rounded-xl border border-brand/30 bg-brand/5 px-3 py-2 text-sm text-brand dark:bg-brand/10">{{ session('status') }}</p>
    @endif

    <form method="post" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full rounded-xl bg-brand py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
            {{ __('aldawy.verify_email_resend') }}
        </button>
    </form>

    <form method="post" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full text-sm font-semibold text-slate-500 hover:text-brand dark:text-slate-400">
            {{ __('aldawy.nav_logout') }}
        </button>
    </form>
@endsection
