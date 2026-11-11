@php
    /** @var \App\Models\InvoiceDesign $design */
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
    $currency = config('aldawy.currency', 'EGP');
    $money = fn ($amount): string => number_format((float) $amount, 2).' '.$currency;
    $qty = function ($amount): string {
        $formatted = rtrim(rtrim(number_format((float) $amount, 2, '.', ''), '0'), '.');

        return $formatted === '' ? '0' : $formatted;
    };
    $header = $design->header();
    $accent = $design->accent();
    $tagline = $design->localized('tagline');
    $address = $design->localized('address');
    $thanks = $design->localized('thank_you');
    $footer = $design->localized('footer_note');
    $logo = $design->logoFile();
    $paymentKey = 'aldawy.payment_'.($order->payment_gateway ?: 'cod');
    $payment = __($paymentKey);
    if ($payment === $paymentKey) {
        $payment = (string) ($order->payment_gateway ?: '—');
    }
    $customer = trim((string) ($order->customer_name ?: $order->user?->name ?: ''));
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: dejavusans, sans-serif; font-size: 11px; color: #1e293b; margin: 0; }
        .header { width: 100%; background: {{ $header }}; color: #ffffff; }
        .header td { padding: 18px 20px; vertical-align: middle; }
        .brand { font-size: 22px; font-weight: bold; letter-spacing: 0.3px; }
        .tagline { margin-top: 4px; font-size: 11px; color: #d1fae5; }
        .doc { text-align: {{ $isRtl ? 'left' : 'right' }}; }
        .doc-kicker { font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #bbf7d0; }
        .doc-ref { margin-top: 4px; font-size: 16px; font-weight: bold; }
        .doc-date { margin-top: 2px; font-size: 11px; color: #e2e8f0; }
        .logo { height: 48px; margin-bottom: 8px; }
        .wrap { padding: 18px 4px 0; }
        .cards { width: 100%; margin-bottom: 16px; }
        .cards td { width: 50%; vertical-align: top; background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 14px; }
        .cards td + td { border-{{ $isRtl ? 'right' : 'left' }}: 0; }
        .label { font-size: 10px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; color: {{ $accent }}; margin-bottom: 6px; }
        .strong { font-size: 13px; font-weight: bold; color: #0f172a; }
        .muted { color: #64748b; line-height: 1.45; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th { background: {{ $header }}; color: #ffffff; font-size: 10px; letter-spacing: 0.4px; text-transform: uppercase; padding: 8px; text-align: {{ $isRtl ? 'right' : 'left' }}; }
        table.items td { padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: {{ $isRtl ? 'right' : 'left' }}; }
        table.items tr.alt td { background: #f8fafc; }
        .num { text-align: {{ $isRtl ? 'left' : 'right' }}; white-space: nowrap; }
        .totals { width: 100%; margin-top: 12px; }
        .totals td { padding: 4px 0; }
        .totals .name { text-align: {{ $isRtl ? 'left' : 'right' }}; color: #64748b; padding-right: 16px; }
        .totals .value { text-align: {{ $isRtl ? 'left' : 'right' }}; width: 120px; font-weight: bold; }
        .grand td { border-top: 2px solid {{ $accent }}; padding-top: 8px; font-size: 14px; color: {{ $header }}; }
        .thanks { margin-top: 22px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px 14px; color: #166534; }
        .footer { margin-top: 10px; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>
    <table class="header" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                @if ($logo)
                    <img class="logo" src="{{ $logo }}" alt="">
                @endif
                <div class="brand">{{ $design->brand_name }}</div>
                @if ($tagline !== '')
                    <div class="tagline">{{ $tagline }}</div>
                @endif
            </td>
            <td class="doc">
                <div class="doc-kicker">{{ __('aldawy.invoice_title') }}</div>
                <div class="doc-ref">{{ $order->reference }}</div>
                <div class="doc-date">{{ $order->created_at?->timezone(config('analytics.timezone', config('app.timezone')))->format('Y-m-d H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="wrap">
        <table class="cards" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="label">{{ __('aldawy.invoice_from') }}</div>
                    <div class="strong">{{ $design->brand_name }}</div>
                    <div class="muted">
                        @if ($address !== ''){{ $address }}<br>@endif
                        @if (filled($design->phone)){{ $design->phone }}<br>@endif
                        @if (filled($design->email)){{ $design->email }}@endif
                    </div>
                </td>
                <td>
                    <div class="label">{{ __('aldawy.invoice_bill_to') }}</div>
                    <div class="strong">{{ $customer !== '' ? $customer : $order->customer_phone }}</div>
                    <div class="muted">
                        @if ($customer !== '' && filled($order->customer_phone)){{ $order->customer_phone }}<br>@endif
                        @if (filled($order->customer_email)){{ $order->customer_email }}<br>@endif
                        @if ($order->shipping_address_line1)
                            {{ $order->shipping_address_line1 }}
                            @if ($order->shipping_address_line2) — {{ $order->shipping_address_line2 }}@endif
                            <br>
                        @endif
                        @if ($order->city){{ $order->city->getTranslation('name', $locale) }}@endif
                    </div>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('aldawy.invoice_item') }}</th>
                    <th>{{ __('aldawy.invoice_unit') }}</th>
                    <th class="num">{{ __('aldawy.invoice_qty') }}</th>
                    <th class="num">{{ __('aldawy.invoice_line_total') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order->items as $i => $item)
                    @php
                        $names = $item->product_name_snapshot ?? [];
                        $label = is_array($names) ? ($names[$locale] ?? $names['en'] ?? $names['ar'] ?? reset($names) ?: '—') : '—';
                    @endphp
                    <tr class="{{ $i % 2 === 1 ? 'alt' : '' }}">
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $label }}</td>
                        <td>
                            {{ \App\Support\StoreCart::unitLabel((string) $item->unit) }}
                            @if ($item->origin)
                                · {{ \App\Support\StoreCart::originLabel((string) $item->origin) }}
                            @endif
                        </td>
                        <td class="num">{{ $qty($item->quantity) }}</td>
                        <td class="num">{{ $money($item->line_total) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">—</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="totals" cellpadding="0" cellspacing="0">
            <tr>
                <td></td>
                <td class="name">{{ __('aldawy.invoice_subtotal') }}</td>
                <td class="value">{{ $money($order->subtotal) }}</td>
            </tr>
            @if ((float) $order->discount_amount > 0)
                <tr>
                    <td></td>
                    <td class="name">{{ __('aldawy.invoice_discount') }}</td>
                    <td class="value">- {{ $money($order->discount_amount) }}</td>
                </tr>
            @endif
            @if ((float) $order->packaging_fee > 0)
                <tr>
                    <td></td>
                    <td class="name">{{ __('aldawy.invoice_packaging') }}</td>
                    <td class="value">{{ $money($order->packaging_fee) }}</td>
                </tr>
            @endif
            <tr>
                <td></td>
                <td class="name">{{ __('aldawy.invoice_shipping') }}</td>
                <td class="value">{{ $money($order->shipping_fee) }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="name">{{ __('aldawy.invoice_payment') }}</td>
                <td class="value">{{ $payment }}</td>
            </tr>
            <tr class="grand">
                <td></td>
                <td class="name">{{ __('aldawy.invoice_total') }}</td>
                <td class="value">{{ $money($order->total) }}</td>
            </tr>
        </table>

        @if ($thanks !== '')
            <div class="thanks">{{ $thanks }}</div>
        @endif
        @if ($footer !== '')
            <div class="footer">{{ $footer }}</div>
        @endif
    </div>
</body>
</html>
