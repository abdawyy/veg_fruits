@php
    $animated = $animated ?? true;
    $sizeKey = $size ?? 'lg';
    $sizeClass = match ($sizeKey) {
        'sm', 'h-3.5 w-3.5' => 'fi-account-chevron--sm',
        'md', 'h-4 w-4' => 'fi-account-chevron--md',
        'lg', 'h-5 w-5' => 'fi-account-chevron--lg',
        default => 'fi-account-chevron--lg',
    };
    $wrapClass = trim(($wrapClass ?? 'fi-account-chevron '.$sizeClass).($animated ? ' fi-account-chevron--animated' : ''));
@endphp

<span class="{{ $wrapClass }}" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
    </svg>
</span>
