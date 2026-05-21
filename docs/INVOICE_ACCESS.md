# Invoice download access

## Routes

| Route | Who | Auth |
|-------|-----|------|
| `GET /my/orders/{order}/invoice` | Logged-in customer | Must own `order.user_id` |
| `GET /checkout/invoice` | Same browser session after checkout | `checkout_order_id` in session |
| `GET /invoices/{order}/download` | Email/SMS signed link | Valid signature (default 14 days) + optional user match |

## Signed links

- Expiry: `ALDAWY_INVOICE_SIGNED_DAYS` (default 14).
- Rate limit: 30 requests/minute per IP.
- If a user is **logged in**, they must own the order (or match guest phone/email on that order). A signed link cannot be used while logged in as a different account.

## Guest orders

Guests receive a signed URL by email/SMS. Treat links as secrets — do not share publicly.
