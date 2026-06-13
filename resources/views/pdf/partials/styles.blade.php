@php
    $isRtl = app()->getLocale() === 'ar';
@endphp
<style>
    body {
        font-family: dejavusans, sans-serif;
        font-size: 12px;
        color: #111;
        direction: {{ $isRtl ? 'rtl' : 'ltr' }};
    }
    h1 { font-size: 18px; margin: 0 0 8px; }
    table { width: 100%; border-collapse: collapse; margin-top: 12px; }
    th, td {
        border: 1px solid #ccc;
        padding: 6px;
        text-align: {{ $isRtl ? 'right' : 'left' }};
    }
    th { background: #f3f4f6; }
    .muted { color: #555; font-size: 11px; }
</style>
