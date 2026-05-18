<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}; }
        .muted { color: #555; font-size: 11px; }
    </style>
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
                    <td>
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
