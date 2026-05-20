import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

function aldawyCsrfToken() {
    const m = document.querySelector('meta[name="csrf-token"]');

    return m ? m.getAttribute('content') : '';
}

function aldawyToast(message) {
    const root = document.getElementById('aldawy-toast');
    if (!root) {
        return;
    }
    const inner = root.querySelector('div');
    if (!inner) {
        return;
    }
    inner.textContent = message;
    root.classList.remove('hidden');
    clearTimeout(root._aldawyT);
    root._aldawyT = setTimeout(() => root.classList.add('hidden'), 3200);
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
            aldawyToast(msg);

            return;
        }
        if (typeof data.cart_line_count === 'number') {
            aldawySetCartCount(data.cart_line_count);
        }
        aldawyToast(data.message || 'Added to cart.');
    } catch {
        aldawyToast('Network error — try again.');
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

document.addEventListener('DOMContentLoaded', aldawyBindProductEstimates);

window.aldawyToast = aldawyToast;
window.aldawySetCartCount = aldawySetCartCount;
