# Account UX: storefront vs `/my`

## Storefront (`/login`, `/register`, `/login/phone`)

- For **guests** placing orders and optional account creation.
- After checkout, guests may see a nudge to register; matching orders link by **email or phone** on login/register.
- Phone OTP login (`/login/phone`) creates or signs into a user with that `phone_number`.

## Customer panel (`/my`)

- Filament **customer** panel (not admin): orders, profile, password.
- Requires **web session** auth (`auth` middleware).
- If `ALDAWY_REQUIRE_EMAIL_VERIFICATION=true`, unverified users are redirected to `/email/verify` before `/my`.

## Admin (`/admin`)

- Separate Filament panel; `users.is_admin` only.

## Consistent expectations

| Action | Where |
|--------|--------|
| Browse & checkout | Storefront |
| Download invoice (logged in) | `/my` → order → invoice |
| Cancel pending order | `/my` → order detail |
| Manage catalog | `/admin` |

Users should use the **same email or phone** at checkout as on their account so guest orders attach correctly.
