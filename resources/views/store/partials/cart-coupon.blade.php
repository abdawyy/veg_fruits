<h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('aldawy.coupon_title') }}</h3>
@if ($appliedCoupon)
    <div class="mt-3 rounded-xl border border-brand/25 bg-brand/5 px-4 py-3 dark:bg-brand/10">
        <p class="text-sm font-semibold text-brand">{{ __('aldawy.coupon_active', ['code' => $couponCode]) }}</p>
        <p class="mt-1 text-xs text-slate-600 dark:text-slate-300">−{{ number_format((float) $discountAmount, 2) }} {{ config('aldawy.currency', 'EGP') }}</p>
    </div>
    <form method="post" action="{{ route('store.cart.coupon.remove') }}" class="mt-3">
        @csrf
        <button type="submit" class="text-sm font-semibold text-danger hover:underline">{{ __('aldawy.coupon_remove') }}</button>
    </form>
@else
    <form method="post" action="{{ route('store.cart.coupon') }}" class="mt-3 flex flex-col gap-2 sm:flex-row">
        @csrf
        <input
            type="text"
            name="coupon_code"
            value="{{ old('coupon_code') }}"
            placeholder="{{ __('aldawy.coupon_placeholder') }}"
            class="aldawy-input min-w-0 flex-1"
        >
        <button type="submit" class="aldawy-btn-primary shrink-0 !px-5 !py-2.5 !text-sm">
            {{ __('aldawy.coupon_apply') }}
        </button>
    </form>
@endif
