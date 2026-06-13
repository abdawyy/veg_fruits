<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    @include('pdf.partials.styles')
    <style>body { font-size: 11px; }</style>
</head>
<body>
    <h1>AL-DAWY — {{ __('aldawy.shop_title') }}</h1>
    <p class="muted">{{ now()->toDateTimeString() }}</p>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th lang="en">{{ __('aldawy.invoice_item') }} (EN)</th>
                <th lang="ar">{{ __('aldawy.invoice_item') }} (AR)</th>
                <th>Category</th>
                <th>{{ __('aldawy.invoice_line_total') }}</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td lang="en">{{ $product->getTranslation('name', 'en') }}</td>
                    <td lang="ar">{{ $product->getTranslation('name', 'ar') }}</td>
                    <td>{{ $product->category?->getTranslation('name', app()->getLocale()) ?? '—' }}</td>
                    <td>{{ number_format((float) $product->price_per_kg, 2) }}</td>
                    <td>{{ $product->is_active ? 'yes' : 'no' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
