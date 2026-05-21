# AL-DAWY — Fresh produce storefront

Laravel 13 e-commerce for **AL-DAWY** (Egypt, EGP): public shop, Filament admin (`/admin`), customer account (`/my`), and a minimal API.

## Documentation

| Doc | Purpose |
|-----|---------|
| [MARKETING_GUIDE.md](./MARKETING_GUIDE.md) | **Product & marketing** — fruits, veg, offers (for humans & AI) |
| [PROJECT_ANALYSIS.md](./PROJECT_ANALYSIS.md) | Architecture, routes, data model |
| [PROJECT_GAPS_AND_ISSUES.md](./PROJECT_GAPS_AND_ISSUES.md) | Gaps and risks |
| [TODO.md](./TODO.md) | Master backlog (Phases A–E) |
| [docs/VERIFICATION.md](./docs/VERIFICATION.md) | Code audit vs backlog (2026-05-20) |
| [docs/PACKAGING_FEES.md](./docs/PACKAGING_FEES.md) | Order vs line packaging fees |
| [docs/CMS_AND_LANG.md](./docs/CMS_AND_LANG.md) | When to use CMS vs lang files |
| [docs/ACCOUNT_UX.md](./docs/ACCOUNT_UX.md) | Storefront login vs `/my` |
| [docs/INVOICE_ACCESS.md](./docs/INVOICE_ACCESS.md) | Invoice download routes & security |

## Requirements

- PHP 8.3+
- Composer, Node.js (for Vite assets)
- SQLite/MySQL/PostgreSQL

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
php artisan serve
```

Admin user (after seed): `admin@aldawy.local` / `password` — see [DatabaseSeeder](./database/seeders/DatabaseSeeder.php).

Run a queue worker so order PDFs, email, and SMS run after checkout:

```bash
php artisan queue:work
```

## Tests & CI

```bash
php artisan test
```

GitHub Actions: [.github/workflows/tests.yml](./.github/workflows/tests.yml) (PHP 8.3, SQLite).

## Key URLs

| URL | Description |
|-----|-------------|
| `/` | Storefront home |
| `/shop`, `/cart`, `/boxes` | Catalog & cart |
| `/login`, `/login/phone` | Email or OTP login |
| `/my` | Customer account (Filament) |
| `/admin` | Admin panel |

## API (v1)

- `GET /api/v1/catalog/categories`
- `GET /api/v1/catalog/products`
- `POST /api/v1/cart/quote`
- `POST /api/v1/orders`

Sanctum: `GET /api/user`, `GET /api/orders` (authenticated).

## Environment highlights

See [.env.example](./.env.example) for SMS (`ALDAWY_SMS_DRIVER`), online payments (`ALDAWY_ONLINE_PAYMENT_ENABLED`), email verification, and phone OTP (`ALDAWY_PHONE_OTP_ENABLED`).

## License

MIT (application code). Product images may use third-party stock URLs per [lang copy](./lang/en/aldawy.php).
