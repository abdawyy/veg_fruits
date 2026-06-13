<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    @include('pdf.partials.styles')
</head>
<body>
    <h1>{{ __('aldawy.invoice_title') }}</h1>
    <p class="muted">{{ $senderName }}</p>
    <p><strong>{{ __('aldawy.invoice_reference') }}:</strong> {{ $order->reference }}</p>
    <p><strong>{{ __('aldawy.invoice_date') }}:</strong> {{ $order->created_at?->format('Y-m-d H:i') }}</p>
    <p><strong>{{ __('aldawy.invoice_phone') }}:</strong> {{ $order->customer_phone }}</p>
    @if ($order->city)
        <p><strong>{{ __('aldawy.invoice_city') }}:</strong>
            {{ $order->city->getTranslation('name', app()->getLocale()) }}
        </p>
    @endif
    @if ($order->shipping_address_line1)
        <p><strong>{{ __('aldawy.invoice_address') }}:</strong> {{ $order->shipping_address_line1 }}
            @if ($order->shipping_address_line2)
                — {{ $order->shipping_address_line2 }}
            @endif
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('aldawy.invoice_item') }}</th>
                <th>{{ __('aldawy.invoice_unit') }}</th>
                <th>{{ __('aldawy.invoice_qty') }}</th>
                <th>{{ __('aldawy.invoice_line_total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td lang="{{ app()->getLocale() }}">
                        @php
                            $names = $item->product_name_snapshot ?? [];
                            $label = is_array($names) ? ($names[app()->getLocale()] ?? reset($names) ?? '—') : '—';
                        @endphp
                        {{ $label }}
                    </td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->line_total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:16px;"><strong>{{ __('aldawy.invoice_subtotal') }}:</strong> {{ $order->subtotal }}</p>
    <p><strong>{{ __('aldawy.invoice_packaging') }}:</strong> {{ $order->packaging_fee }}</p>
    <p><strong>{{ __('aldawy.invoice_shipping') }}:</strong> {{ $order->shipping_fee }}</p>
    <p><strong>{{ __('aldawy.invoice_total') }}:</strong> {{ $order->total }}</p>
</body>
</html>
