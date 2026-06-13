<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    @include('pdf.partials.styles')
    <style>body { font-size: 10px; } th, td { padding: 3px 5px; }</style>
</head>
<body>
    <h1>AL-DAWY — {{ __('aldawy.account_tile_orders') }}</h1>
    <p class="muted">{{ now()->toDateTimeString() }} — max 500</p>
    <table>
        <thead>
            <tr>
                <th>{{ __('aldawy.invoice_reference') }}</th>
                <th>Status</th>
                <th>{{ __('aldawy.email') }}</th>
                <th>{{ __('aldawy.invoice_phone') }}</th>
                <th>{{ __('aldawy.invoice_total') }}</th>
                <th>{{ __('aldawy.invoice_date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->reference }}</td>
                    <td lang="{{ app()->getLocale() }}">{{ $order->status->label() }}</td>
                    <td>{{ $order->user?->email ?? $order->customer_email ?? '—' }}</td>
                    <td>{{ $order->customer_phone ?? '—' }}</td>
                    <td>{{ number_format((float) $order->total, 2) }}</td>
                    <td>{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
