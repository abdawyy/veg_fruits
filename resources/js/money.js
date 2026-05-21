/**
 * Integer minor-unit money math (avoids JS float rounding on checkout display).
 */
export function aldawyMoneyToCents(value) {
    const s = String(value ?? '0').trim().replace(/,/g, '');
    const negative = s.startsWith('-');
    const body = negative ? s.slice(1) : s;
    const parts = body.split('.');
    const whole = parts[0] === '' ? '0' : parts[0];
    let frac = (parts[1] || '').replace(/\D/g, '');
    if (frac.length > 2) {
        frac = frac.slice(0, 2);
    }
    while (frac.length < 2) {
        frac += '0';
    }
    const cents = parseInt(whole, 10) * 100 + parseInt(frac, 10);

    return negative ? -cents : cents;
}

export function aldawyFormatCents(cents) {
    const n = Number(cents) || 0;
    const negative = n < 0;
    const abs = Math.abs(n);
    const whole = Math.floor(abs / 100);
    const frac = String(abs % 100).padStart(2, '0');

    return (negative ? '-' : '') + whole + '.' + frac;
}

export function aldawySumCents(...values) {
    return values.reduce((sum, v) => sum + (Number(v) || 0), 0);
}
