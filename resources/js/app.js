import './bootstrap';
import { aldawyFormatCents, aldawyMoneyToCents, aldawySumCents } from './money';
import Alpine from 'alpinejs';

window.aldawyMoneyToCents = aldawyMoneyToCents;
window.aldawyFormatCents = aldawyFormatCents;
window.aldawySumCents = aldawySumCents;

window.Alpine = Alpine;

Alpine.data('homeBannerCarousel', (total) => ({
    active: 0,
    total: Number(total) || 1,
    interval: 6800,
    timer: null,
    dragging: false,
    startX: 0,
    init() {
        this.start();
    },
    go(index) {
        if (this.total < 1) {
            return;
        }
        const next = ((index % this.total) + this.total) % this.total;
        if (next === this.active) {
            return;
        }
        this.active = next;
        this.start();
    },
    next() {
        this.go(this.active + 1);
    },
    prev() {
        this.go(this.active - 1);
    },
    start() {
        this.stop();
        if (this.total < 2) {
            return;
        }
        this.timer = setInterval(() => this.next(), this.interval);
    },
    stop() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    },
    trackStyle() {
        return `transform: translate3d(${this.active * -100}%, 0, 0)`;
    },
    pointerDown(event) {
        this.dragging = true;
        this.startX = event.clientX ?? event.touches?.[0]?.clientX ?? 0;
        this.stop();
    },
    pointerUp(event) {
        if (! this.dragging) {
            return;
        }
        const x = event.clientX ?? event.changedTouches?.[0]?.clientX ?? this.startX;
        const delta = x - this.startX;
        this.dragging = false;
        if (delta > 56) {
            this.prev();
        } else if (delta < -56) {
            this.next();
        } else {
            this.start();
        }
    },
}));

Alpine.start();

function aldawyCsrfToken() {
    const m = document.querySelector('meta[name="csrf-token"]');

    return m ? m.getAttribute('content') : '';
}

const TOAST_ICONS = {
    success: '✓',
    error: '!',
    info: 'i',
};

function aldawyToast(message, options = {}) {
    const root = document.getElementById('aldawy-toast-root');
    if (!root || !message) {
        return;
    }

    const body = document.body;
    const type = options.type || 'success';
    const duration = typeof options.duration === 'number' ? options.duration : 4800;
    const action = options.action || null;
    const dismissLabel = body.dataset.toastDismiss || 'Dismiss';

    const toast = document.createElement('div');
    toast.className = `aldawy-toast aldawy-toast--${type}`;
    toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

    const icon = document.createElement('span');
    icon.className = 'aldawy-toast-icon';
    icon.setAttribute('aria-hidden', 'true');
    icon.textContent = TOAST_ICONS[type] || TOAST_ICONS.info;

    const content = document.createElement('div');
    content.className = 'aldawy-toast-content';

    const text = document.createElement('p');
    text.className = 'aldawy-toast-message';
    text.textContent = message;
    content.appendChild(text);

    if (action?.href && action?.label) {
        const link = document.createElement('a');
        link.href = action.href;
        link.className = 'aldawy-toast-action';
        link.textContent = action.label;
        content.appendChild(link);
    }

    const close = document.createElement('button');
    close.type = 'button';
    close.className = 'aldawy-toast-close';
    close.setAttribute('aria-label', dismissLabel);
    close.innerHTML = '<svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>';

    const bar = document.createElement('span');
    bar.className = 'aldawy-toast-progress';
    bar.style.animationDuration = `${duration}ms`;

    toast.append(icon, content, close, bar);
    root.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('is-visible'));

    const dismiss = () => {
        toast.classList.remove('is-visible');
        toast.classList.add('is-leaving');
        setTimeout(() => toast.remove(), 280);
    };

    close.addEventListener('click', dismiss);
    const timer = setTimeout(dismiss, duration);
    toast._aldawyTimer = timer;
}

function aldawySetCartCount(n) {
    document.querySelectorAll('.aldawy-cart-badge').forEach((badge) => {
        const countEl = badge.querySelector('.aldawy-cart-count');
        if (countEl) {
            countEl.textContent = String(n);
        }
        badge.classList.toggle('hidden', n <= 0);
    });
}

document.addEventListener('submit', async (e) => {
    const form = e.target.closest('form[data-ajax-cart]');
    if (!form) {
        return;
    }
    e.preventDefault();
    const btn = form.querySelector('[type="submit"]');
    const fd = new FormData(form);
    const prev = btn ? btn.textContent : '';
    if (btn) {
        btn.disabled = true;
        btn.textContent = '…';
    }
    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': aldawyCsrfToken(),
            },
            body: fd,
            credentials: 'same-origin',
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            const msg =
                data.message ||
                (data.errors && Object.values(data.errors).flat().join(' ')) ||
                'Could not add to cart.';
            aldawyToast(msg, { type: 'error' });

            return;
        }
        if (typeof data.cart_line_count === 'number') {
            aldawySetCartCount(data.cart_line_count);
        }
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('cart-updated');
        }
        const cartUrl = document.body.dataset.cartUrl || '/cart';
        const viewCartLabel = document.body.dataset.toastViewCart || 'View cart';
        aldawyToast(data.message || 'Added to cart.', {
            type: 'success',
            action: { href: cartUrl, label: viewCartLabel },
        });
    } catch {
        aldawyToast('Network error — try again.', { type: 'error' });
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.textContent = prev;
        }
    }
});

function aldawyBindProductEstimates() {
    document.querySelectorAll('form[data-product-estimate]').forEach((form) => {
        const url = form.getAttribute('data-product-estimate');
        const out = form.querySelector('[data-estimate-value]');
        if (!url || !out) {
            return;
        }
        let timer = null;
        const run = async () => {
            const fd = new FormData(form);
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': aldawyCsrfToken(),
                    },
                    body: fd,
                    credentials: 'same-origin',
                });
                const data = await res.json().catch(() => ({}));
                if (res.ok && data.formatted) {
                    out.textContent = data.formatted;
                } else {
                    out.textContent = '—';
                }
            } catch {
                out.textContent = '—';
            }
        };
        const schedule = () => {
            clearTimeout(timer);
            timer = setTimeout(run, 280);
        };
        form.querySelectorAll('input, select').forEach((el) => {
            el.addEventListener('input', schedule);
            el.addEventListener('change', schedule);
        });
        schedule();
    });
}

function aldawyInitFlashToasts() {
    document.querySelectorAll('[data-flash-message]').forEach((el) => {
        const msg = el.getAttribute('data-flash-message');
        const type = el.getAttribute('data-flash-type') || 'success';
        if (msg) {
            aldawyToast(msg, { type, duration: 6000 });
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    aldawyBindProductEstimates();
    aldawyInitFlashToasts();
});

window.aldawyToast = aldawyToast;
window.aldawySetCartCount = aldawySetCartCount;
