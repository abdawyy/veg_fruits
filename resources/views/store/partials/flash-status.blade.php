@if (session('status'))
    <div
        class="hidden"
        data-flash-message="{{ e(session('status')) }}"
        data-flash-type="success"
        aria-hidden="true"
    ></div>
@endif
