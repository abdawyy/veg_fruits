<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 3px 5px; text-align: left; }
        th { background: #f3f4f6; }
        h1 { font-size: 16px; margin: 0 0 8px; }
    </style>
</head>
<body>
    <h1>AL-DAWY — Orders summary</h1>
    <p>{{ now()->toDateTimeString() }} — max 500 rows</p>
    <table>
        <thead>
            <tr>
                <th>Ref</th>
                <th>Status</th>
                <th>User</th>
                <th>Customer email</th>
                <th>Phone</th>
                <th>Total</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->reference }}</td>
                    <td>{{ $order->status->value }}</td>
                    <td>{{ $order->user?->email ?? '—' }}</td>
                    <td>{{ $order->customer_email ?? '—' }}</td>
                    <td>{{ $order->customer_phone ?? '—' }}</td>
                    <td>{{ number_format((float) $order->total, 2) }}</td>
                    <td>{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
