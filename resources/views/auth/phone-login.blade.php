@extends('layouts.store')

@section('content')
    <div class="mx-auto max-w-md px-4 py-16">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('aldawy.phone_login_title') }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ __('aldawy.phone_login_sub') }}</p>

        @if ($errors->any())
            <ul class="mt-4 list-inside list-disc text-sm text-danger">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        @endif
        @if (session('status'))
            <p class="mt-4 text-sm text-brand">{{ session('status') }}</p>
        @endif

        @if ($step === 'phone')
            <form method="post" action="{{ route('phone.login.send') }}" class="mt-8 space-y-4">
                @csrf
                <input type="hidden" name="redirect" value="{{ $redirect }}">
                <div>
                    <label for="phone" class="block text-sm font-medium">{{ __('aldawy.checkout_phone') }}</label>
                    <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 dark:border-slate-700 dark:bg-slate-950">
                </div>
                <button type="submit" class="w-full rounded-xl bg-brand py-3 text-sm font-bold text-white">{{ __('aldawy.otp_send') }}</button>
            </form>
        @else
            <form method="post" action="{{ route('phone.login.verify.submit') }}" class="mt-8 space-y-4">
                @csrf
                <input type="hidden" name="redirect" value="{{ $redirect }}">
                <input type="hidden" name="phone" value="{{ $phone }}">
                <p class="text-sm text-slate-600">{{ __('aldawy.otp_sent_to', ['phone' => $phone]) }}</p>
                <div>
                    <label for="code" class="block text-sm font-medium">{{ __('aldawy.otp_code_label') }}</label>
                    <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 dark:border-slate-700 dark:bg-slate-950">
                </div>
                <button type="submit" class="w-full rounded-xl bg-brand py-3 text-sm font-bold text-white">{{ __('aldawy.otp_verify') }}</button>
            </form>
        @endif

        <p class="mt-6 text-center text-sm">
            <a href="{{ route('login') }}" class="font-semibold text-brand hover:underline">{{ __('aldawy.back_to_login') }}</a>
        </p>
    </div>
@endsection
