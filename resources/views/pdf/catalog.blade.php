<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #f3f4f6; }
        h1 { font-size: 18px; margin: 0 0 8px; }
    </style>
</head>
<body>
    <h1>AL-DAWY — Product catalog</h1>
    <p>{{ now()->toDateTimeString() }}</p>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name (EN)</th>
                <th>Category</th>
                <th>Price / kg</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->getTranslation('name', 'en') }}</td>
                    <td>{{ $product->category?->getTranslation('name', 'en') ?? '—' }}</td>
                    <td>{{ number_format((float) $product->price_per_kg, 2) }}</td>
                    <td>{{ $product->is_active ? 'yes' : 'no' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
