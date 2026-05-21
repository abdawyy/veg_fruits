# AL-DAWY — Project Analysis

> **Purpose:** Single source of truth for developers and AI coding agents working on this repository.  
> **Brand:** AL-DAWY — fresh fruits & vegetables e-commerce (Egypt-focused, EGP currency).  
> **Composer package:** `aldawy/storefront`  
> **Related:** [PROJECT_GAPS_AND_ISSUES.md](./PROJECT_GAPS_AND_ISSUES.md) — historical audit + what shipped (Phases A–E)  
> **Backlog:** [TODO.md](./TODO.md) · **Verification:** [docs/VERIFICATION.md](./docs/VERIFICATION.md)  
> **Last updated:** 2026-05-20 (post Phases A–E)

---

## 1. Executive summary

AL-DAWY is a **Laravel 13** monolith that powers:

| Surface | Path | Audience |
|---------|------|----------|
| **Public storefront** | `/`, `/shop`, `/cart`, `/checkout` | Customers (guest or logged-in) |
| **Admin panel** (Filament v5) | `/admin` | Staff (`users.is_admin = true`) |
| **Customer account** (Filament) | `/my` | Registered customers |
| **REST API** (Sanctum) | `/api/v1/*` + `/api/user`, `/api/orders` | Mobile / integrations |

**Core business:** Sell produce by **kg** or **piece**, optional **preparation** and **packaging** surcharges, **produce boxes** (one-time or **subscription**). Checkout: **COD** (default) and optional **online pending** payment. **Coupons**, optional **stock tracking**. Orders trigger **queued** PDF invoice, **email**, and **SMS** (`log` or `http` driver).

**Languages:** English (`en`) and Arabic (`ar`) via Spatie Translatable on models + `lang/{locale}/aldawy.php` + CMS content strings.

---

## 2. Technology stack

| Layer | Technology |
|-------|------------|
| Runtime | PHP **8.3+** |
| Framework | **Laravel 13** |
| Admin UI | **Filament 5.6** (two panels: admin + account) |
| Interactivity | **Livewire 4.3** (store components: search bar, price banner) |
| Frontend build | **Vite 7**, **Tailwind CSS 4**, **Alpine.js 3** |
| PDF | `barryvdh/laravel-dompdf` |
| Excel import/export | `maatwebsite/excel` |
| i18n (DB fields) | `spatie/laravel-translatable` |
| API auth | `laravel/sanctum` |
| DB (default in `.env.example`) | **PostgreSQL** |
| Cache (default) | **Redis** |
| Sessions | **Database** driver |
| Queues | **Database** connection |

---

## 3. Repository layout

```
fruits&veg/
├── app/
│   ├── Actions/           # Orders, invoices, subscriptions, auth (OTP), coupons
│   ├── Console/Commands/  # Scheduled artisan commands
│   ├── Contracts/         # Payment + SMS interfaces
│   ├── DTO/               # Typed payloads (cart lines, order creation)
│   ├── Enums/             # OrderStatus, SubscriptionInterval
│   ├── Events/            # OrderCreated
│   ├── Exports/           # Maatwebsite Excel exports (admin)
│   ├── Filament/          # Admin panel resources, widgets, pages
│   ├── Filament/Account/  # Customer panel (my orders)
│   ├── Http/
│   │   ├── Controllers/   # Storefront, cart, checkout, auth, API v1, invoices
│   │   ├── Middleware/    # Locale, visitor tracking, Filament auth
│   │   └── Resources/     # API JSON transformers
│   ├── Imports/           # Excel imports
│   ├── Listeners/         # OrderCreated → invoice + notify
│   ├── Models/            # Eloquent models (17)
│   ├── Notifications/     # Mail notifications
│   ├── Payments/          # COD + OnlinePendingPaymentGateway
│   ├── Providers/         # AppServiceProvider + Filament panels
│   ├── Services/          # Cart totals, pricing, money math, product price updates
│   ├── Sms/               # LogSmsSender, HttpSmsSender
│   └── Support/           # StoreCart, MoneyCents, Cms, StoreSeo, SpreadsheetImportRunner
├── bootstrap/app.php      # Middleware registration, routing
├── config/
│   ├── aldawy.php         # Currency, decimal scale, invoice sender name
│   └── analytics.php      # GA4 + Meta Pixel IDs
├── database/
│   ├── migrations/        # Schema (commerce + CMS + analytics)
│   └── seeders/           # Catalog, operations, banners, admin user
├── lang/en|ar/aldawy.php  # Storefront UI strings
├── resources/views/       # Blade: store, auth, pdf, filament overrides
├── routes/
│   ├── web.php            # Storefront + auth + signed invoice download
│   ├── api.php            # Sanctum + v1 catalog, cart quote, create order
│   └── console.php        # Subscription scheduler
└── tests/                 # Feature + unit (cart, checkout, totals)
```

---

## 4. Architecture patterns

### 4.1 Layering convention

| Pattern | Where | Example |
|---------|-------|---------|
| **Action classes** | `app/Actions/*` | `CreateOrderAction`, `GenerateInvoicePdfAction` |
| **Services** | `app/Services/*` | `CalculateCartTotalService`, `SurchargeOnBase` |
| **DTOs** | `app/DTO/*` | `CreateOrderPayload`, `CartLineDto` |
| **Support (static helpers)** | `app/Support/*` | `StoreCart` (session cart), `Cms` |
| **Contracts + bindings** | `AppServiceProvider` | `PaymentGatewayInterface` → `CashOnDeliveryGateway` |
| **Domain events** | `OrderCreated` | Listener generates PDF, sends notifications |

### 4.2 Money handling

- All money uses **BCMath** via `App\Services\Money\DecimalMath` with scale from `config('aldawy.decimal_scale')` (default **4**).
- Prices stored as `decimal(14,4)` in DB.
- Currency label: `config('aldawy.currency')` → **EGP**.

### 4.3 Pricing formula (per cart line)

```
line_base       = unit_price × quantity (kg)
services_fee    = sum of preparation surcharges on line_base
after_services  = line_base + services_fee
packaging_fee   = packaging surcharge on after_services (if selected)
line_subtotal   = after_services + packaging_fee
```

Surcharges can be **fixed amount** or **percent of base** (`surcharge_is_percent` on `PreparationService` / `PackagingType`).

Order total:

```
order.subtotal      = Σ line totals (from CalculateCartTotalService)
order.packaging_fee = order-level fee (storefront checkout passes 0; per-line packaging in surcharges)
order.discount_amount = coupon discount (optional)
order.shipping_fee  = city.shipping_fee
order.total         = subtotal − discount + packaging_fee + shipping_fee
```

### 4.4 Cart storage

- **Session keys:** `aldawy_cart`, `aldawy_coupon_code`
- **Line kinds:** `product` (unit `kg` | `piece`, quantity) or `box` (produce box, qty 1)
- Product lines: `{ type, product_id, unit, quantity, preparation_service_ids[], packaging_type_id?, unit_price_snapshot? }`
- Lines merge on product+unit+prep+packaging or box id
- `resolved()` drops inactive products, out-of-stock lines, and invalid units
- **Coupon:** `ApplyCouponService` at cart display and checkout

---

## 5. Domain model

### 5.1 Entity relationship (simplified)

```mermaid
erDiagram
    User ||--o{ Order : places
    User ||--o{ Subscription : has
    City ||--o{ Order : ships_to
    Category ||--o{ Product : contains
    Product }o--o{ PreparationService : "prep_svc pivot"
    Product }o--o{ PackagingType : "pkg pivot"
    Order ||--|{ OrderItem : contains
    Product ||--o{ OrderItem : "nullable FK"
    ProduceBox ||--o{ ProduceBoxItem : contains
    ProduceBox ||--o{ Subscription : "box subscription"
    ProduceBoxItem }o--|| Product : references
```

### 5.2 Core tables

| Table | Role |
|-------|------|
| `categories` | Fruit/veg groupings; JSON `name`, `slug` (translatable) |
| `products` | SKU, `price_per_kg`, `price_per_piece`, `track_stock`, `stock_quantity`, images |
| `coupons` | Percent/fixed discounts, usage limits, date range |
| `import_audit_logs` | Excel import dry-run/commit results |
| `preparation_services` | Code, surcharge rules, sort order |
| `packaging_types` | Code, surcharge rules, sort order |
| `preparation_service_product` | Per-product enablement |
| `packaging_type_product` | Per-product enablement |
| `produce_boxes` | Curated box SKUs (subscription-ready) |
| `produce_box_items` | Box composition |
| `orders` | Reference `AL-XXXXXXXXXX`, `coupon_id`, `discount_amount`, fees, status, `invoice_path` |
| `order_items` | Snapshots: `product_name_snapshot`, unit, qty, services JSON, packaging code |
| `subscriptions` | Recurring box orders; cron creates orders via `ProcessDueSubscriptionsAction` |
| `cities` | Shipping zones + `shipping_fee` |
| `content_strings` | CMS key → en/ar values (cached forever) |
| `seo_settings` | Global + per-route meta, OG image |
| `home_banners` | Carousel slides |
| `site_visitors` | Session-based analytics (hashed IP/UA) |
| `site_page_views` | Per-path view log |
| `phone_verifications` | OTP for `/login/phone` storefront auth |

### 5.3 Order status lifecycle

`App\Enums\OrderStatus` (string-backed enum):

`pending` → `confirmed` → `processing` → `shipped` → `delivered` | `cancelled`

New orders start as **`pending`** in `CreateOrderAction`.

### 5.4 User roles

| Field | Meaning |
|-------|---------|
| `is_admin = true` | Can access `/admin` Filament panel |
| Any authenticated user | Can access `/my` account panel |
| Guest checkout | Allowed (`user_id` nullable on orders) |

Default seeded admin: `admin@aldawy.local` / `password` (see `DatabaseSeeder`).

---

## 6. HTTP routes & flows

### 6.1 Storefront (`routes/web.php`)

| Method | URI | Controller | Name |
|--------|-----|------------|------|
| GET | `/` | StorefrontController@home | store.home |
| GET | `/shop` | StorefrontController@shop | store.shop |
| GET | `/fruits` | StorefrontController@fruits | store.fruits |
| GET | `/vegetables` | StorefrontController@vegetables | store.vegetables |
| GET | `/products/{product}` | StorefrontController@product | store.product |
| GET | `/special-services` | StorefrontController@services | store.services |
| GET | `/boxes`, `/boxes/{box}` | ProduceBoxController | store.boxes.* |
| POST | `/boxes/{box}/cart`, `/subscribe` | ProduceBox / Subscription | auth for subscribe |
| GET/POST | `/cart/*` | CartController | store.cart.* (incl. coupon) |
| POST | `/checkout` | CheckoutController@store | store.checkout.store |
| GET | `/checkout/thanks`, `/checkout/invoice` | CheckoutController / session invoice | |
| GET/POST | `/login`, `/register`, `/login/phone` | Auth | email, OTP |
| GET | `/forgot-password`, `/reset-password` | Password reset | |
| GET | `/email/verify` | Email verification (optional) | |
| GET | `/invoices/{order}/download` | InvoiceDownloadController | signed + auth rules |
| GET | `/my/orders/{order}/invoice` | AccountInvoiceDownloadController | auth owner |

### 6.2 Checkout flow

```
Cart (session) → POST /checkout (validate city, address, phone)
  → CheckoutController builds OrderLineDraftDto[] from StoreCart::resolved()
  → CreateOrderAction (DB transaction)
  → PaymentGatewayInterface::handleCheckout (COD)
  → OrderCreated event
  → OrderCreated → **queued** listener: PDF + SMS + emails
  → Clear cart → thanks page (`checkout_order_id` in session)
```

### 6.3 API (`routes/api.php`)

| Endpoint | Auth | Purpose |
|----------|------|---------|
| `GET /api/user` | Sanctum | Current user |
| `GET /api/orders` | Sanctum | Paginated own orders |
| `GET /api/v1/catalog/categories` | Public | Category list |
| `GET /api/v1/catalog/products` | Public | Product catalog (+ search) |
| `GET /api/v1/catalog/products/{id}` | Public | Single product |
| `POST /api/v1/cart/quote` | Public | Price quote for line payload |
| `POST /api/v1/orders` | Public/guest | Create order (body lines + delivery) |

### 6.4 Middleware (global web stack)

| Middleware | Purpose |
|------------|---------|
| `SetLocaleFromQuery` | `?locale=en|ar` sets `app()->setLocale()` |
| `TrackSiteVisitor` | Upserts `site_visitors`, logs `site_page_views` (skips admin/api/assets) |

---

## 7. Filament admin (`/admin`)

**Provider:** `App\Providers\Filament\AdminPanelProvider`  
**Auth:** `FilamentAuthenticate` + `User::canAccessPanel()` requires `is_admin` for `admin` panel.

### 7.1 Resources (CRUD)

| Resource | Model | Notes |
|----------|-------|-------|
| `CategoryResource` | Category | EN/AR names, slugs |
| `ProductResource` | Product | Stock, translatable fields, prep/packaging, import dry-run |
| `ProduceBoxResource` | ProduceBox | Box CRUD + item repeater |
| `CouponResource` | Coupon | Discount codes |
| `OrderResource` | Order | Translated status labels, items, exports |
| `CityResource` | City | Shipping fees |
| `PreparationServiceResource` | PreparationService | Surcharge rules |
| `PackagingTypeResource` | PackagingType | Surcharge rules |
| `UserResource` | User | Admin flag, order relation manager |
| `ContentStringResource` | ContentString | CMS overrides |
| `SeoSettingResource` | SeoSetting | Meta + OG image |
| `HomeBannerResource` | HomeBanner | Carousel |
| `SiteVisitorResource` | SiteVisitor | Read-only analytics |

### 7.2 Widgets (dashboard)

- `SalesOverviewStats`, `CommerceSnapshotWidget`
- `OrdersOverTimeChart`, `TopProductsByViewsChart`, `TopStorePathsChart`, `TrafficReferrersChart`
- `ActiveVisitorsWidget`

### 7.3 Excel import/export

Admin can import/export via Maatwebsite classes in `app/Imports/*` and `app/Exports/*` for: products, cities, orders, content strings, home banners, site visitors, page views.

### 7.4 Custom pages

- `AdminGuide` — in-app documentation for staff

---

## 8. Customer account panel (`/my`)

**Provider:** `App\Providers\Filament\AccountPanelProvider`  
**Path:** `/my`  
**Features:** Profile, `CustomerDashboard`, `MyOrderResource` (list/view, **invoice download**, **cancel** pending/confirmed orders)  
**Auth:** Any logged-in user; optional `ALDAWY_REQUIRE_EMAIL_VERIFICATION`.

Storefront auth: email/password, **password reset**, **phone OTP** (`/login/phone`), guest order **linking** on login/register.

---

## 9. Key classes reference

### 9.1 Order creation

```
CheckoutController
  → CreateOrderPayload + OrderLineDraftDto[]
  → CreateOrderAction
      → CalculateCartTotalService (line math)
      → City lookup (shipping_fee)
      → Order + OrderItem records
      → PaymentGatewayInterface
      → OrderCreated::dispatch()
```

### 9.2 Post-order listener

`OnOrderCreatedGenerateInvoiceAndNotify` (**implements `ShouldQueue`**):

1. `GenerateInvoicePdfAction` → `orders.invoice_path`
2. Signed download URL (`ALDAWY_INVOICE_SIGNED_DAYS`, default 14)
3. SMS via `SmsSenderInterface` (`log` or `http`)
4. `OrderConfirmationNotification` (localized)
5. `AdminNewOrderNotification` to admins

`OrderObserver` sends queued `OrderStatusChangedNotification` on status updates.

### 9.3 Cart controller

`CartController` — add/update/remove/clear/options, **coupons**, stock checks; AJAX add; dispatches `cart-updated` for Livewire drawer.

### 9.4 Subscriptions

- `SubscribeToProduceBoxAction` — signup + first order
- `ProcessDueSubscriptionsAction` — cron creates renewal orders, advances `next_order_at`
- `aldawy:process-subscriptions` daily 06:00

### 9.5 Produce boxes

- Admin: `ProduceBoxResource`
- Storefront: `/boxes`, cart as `KIND_BOX`, subscription form on box detail page

---

## 10. Views & frontend

| Layout | Used by |
|--------|---------|
| `layouts/store.blade.php` | All storefront pages |
| `layouts/guest.blade.php` | Login/register |
| `partials/aldawy-theme.blade.php` | Tailwind theme tokens (brand green) |
| `partials/storefront-analytics.blade.php` | GA4 + Meta Pixel (env-gated) |

**Livewire/Volt-style components** (⚡ prefix):

- `components/store/⚡product-search-bar.blade.php`
- `components/store/⚡price-notice-banner.blade.php`
- `components/store/⚡cart-preview-drawer.blade.php`

**JS:** `resources/js/money.js` — integer cents for checkout total display (avoids float drift).

**PDF templates:** `resources/views/pdf/invoice.blade.php`, `catalog.blade.php`, `orders-summary.blade.php`

**View composer** (`AppServiceProvider`): injects `cartLineCount`, SEO meta, `cms()` helper into store views.

---

## 11. Localization & CMS

### 11.1 Locales

- Supported: `en`, `ar`
- Switch: query param `?locale=ar` (middleware) or links in UI
- Product/category names: JSON columns via Spatie `HasTranslations`

### 11.2 Translation sources (priority)

1. **CMS** — `ContentString` table, cached in `Cms::text($key, $fallback)`  
2. **Lang files** — `lang/{locale}/aldawy.php` (large storefront dictionary)  
3. **Filament** — uses Laravel `__()` for admin labels  
4. **Guidelines** — [docs/CMS_AND_LANG.md](./docs/CMS_AND_LANG.md)

### 11.3 SEO

`StoreSeo::pageMeta($routeName)` + per-route rows in `seo_settings`. Fallback titles/descriptions from lang keys in `AppServiceProvider` view composer.

---

## 12. Configuration reference

### 12.1 `config/aldawy.php`

| Key | Env | Default |
|-----|-----|---------|
| `invoice_sender_name` | `ALDAWY_INVOICE_SENDER_NAME` | abdelrahman mohamed |
| `currency` | `ALDAWY_CURRENCY` | EGP |
| `decimal_scale` | `ALDAWY_DECIMAL_SCALE` | 4 |
| `invoice_signed_days` | `ALDAWY_INVOICE_SIGNED_DAYS` | 14 |
| `sms.driver` | `ALDAWY_SMS_DRIVER` | log \| http |
| `payments.online_enabled` | `ALDAWY_ONLINE_PAYMENT_ENABLED` | false |
| `require_email_verification` | `ALDAWY_REQUIRE_EMAIL_VERIFICATION` | false |
| `otp.enabled` | `ALDAWY_PHONE_OTP_ENABLED` | true |

### 12.2 Important `.env` variables

```
APP_NAME, APP_URL, APP_LOCALE
DB_* (pgsql default)
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=redis
MAIL_* (log driver in dev)
ALDAWY_* 
ANALYTICS_GA4_MEASUREMENT_ID
ANALYTICS_META_PIXEL_ID
```

---

## 13. Database & seeding

### 13.1 Migrations order (conceptual)

1. Laravel defaults (users, cache, jobs)
2. `2026_05_12_120000_create_aldawy_commerce_tables.php` — core commerce
3. Product images, content_strings, seo, visitors, view_count
4. Home banners, cities, shipping address on orders
5. Visitor insights + page views, banner image upload  
6. Default delivery fields on users, coupons/stock/import audit (Phase E)

### 13.2 Seeders

| Seeder | Purpose |
|--------|---------|
| `ProduceCatalogSeeder` | Categories + products from `database/seeders/Catalog/produce_items.php` |
| `ProduceBoxSeeder` | Sample produce box (after catalog) |
| `CatalogOperationsSeeder` | Prep services, packaging types, pivots |
| `HomeBannerSeeder` | Sample banners |
| `DatabaseSeeder` | Calls above + creates admin user |

Run: `php artisan migrate --seed`

---

## 14. Testing

| Test | Covers |
|------|--------|
| `tests/Unit/CalculateCartTotalServiceTest.php` | Line + order total math |
| `tests/Feature/CartAjaxAddTest.php` | AJAX add to cart |
| `tests/Feature/CheckoutTest.php` | Checkout + queued listener |
| `tests/Feature/PhaseACheckoutGuardsTest.php` | Guards, idempotency |
| `tests/Feature/PhaseDCommerceTest.php` | Boxes, subscriptions |
| `tests/Feature/InvoiceDownloadAuthorizationTest.php` | Invoice auth |

CI: `.github/workflows/tests.yml`. Run: `php artisan test`

---

## 15. Extensibility hooks (for implementers)

### 15.1 Add a payment gateway

1. Implement `App\Contracts\Payments\PaymentGatewayInterface`
2. Bind in `AppServiceProvider::register()`
3. Set `payment_gateway` identifier on orders (already stored)

### 15.2 SMS & payments (configured)

- SMS: set `ALDAWY_SMS_DRIVER=http` and HTTP env vars in `config/aldawy.php`
- Payments: set `ALDAWY_ONLINE_PAYMENT_ENABLED=true` for online pending gateway

### 15.3 Subscriptions & boxes (implemented)

See `ProcessDueSubscriptionsAction`, `SubscribeToProduceBoxAction`, `ProduceBoxController`, `BuildOrderLinesFromProduceBoxAction`.

### 15.4 Sell by piece (implemented)

`StoreCart::UNIT_PIECE`, cart UI unit selector, `CheckoutController` draft mapping.

---

## 16. Security notes

- **Invoice download:** signed URLs (configurable expiry), rate-limited; logged-in users must match order ownership or guest identity — see [docs/INVOICE_ACCESS.md](./docs/INVOICE_ACCESS.md)
- **Checkout:** `checkout_nonce` + `checkout_in_flight`; cart/checkout throttled
- Visitor tracking: hashed IP/UA
- Admin: `is_admin` + Filament auth
- CSRF on web POST; Sanctum on API

---

## 17. Operational commands

```bash
# Full local setup (composer script)
composer setup

# Dev stack (server + queue + logs + vite)
composer dev

# Process subscription cron manually
php artisan aldawy:process-subscriptions

# Code style
./vendor/bin/pint
```

---

## 18. Remaining / optional enhancements

| Area | Notes |
|------|--------|
| **Payment capture** | Online gateway marks pending; no PSP webhook/capture yet |
| **SMS production** | Configure `ALDAWY_SMS_DRIVER=http` for live provider |
| **API cart session** | v1 quote/orders use request body lines, not server session cart |
| **Import audit UI** | `import_audit_logs` table exists; no Filament resource yet |
| **Other Excel imports** | Dry-run runner wired on products; cities/orders can adopt same pattern |

Phases A–E are **shipped** — see [TODO.md](./TODO.md) and [docs/VERIFICATION.md](./docs/VERIFICATION.md).

---

## 19. Conventions for AI agents

When modifying this codebase:

1. **Prefer existing patterns:** Actions for writes, Services for calculations, DTOs for structured input, Support for session/static helpers.
2. **Never use float for money** — use `DecimalMath` / `bc*` functions.
3. **Translatable fields** — use `getTranslation()` / `getTranslations()` on models with `HasTranslations`.
4. **Filament v5** — resources live under `app/Filament/Resources/{Entity}/` with separate `Schemas/`, `Tables/`, `Pages/`.
5. **Storefront strings** — add keys to both `lang/en/aldawy.php` and `lang/ar/aldawy.php`.
6. **Cart changes** — update `StoreCart`, `CartController`, and checkout draft mapping in `CheckoutController`.
7. **Order side effects** — hook into `OrderCreated` event, not inline in controller after create.
8. **Tests** — run `php artisan test` after cart/checkout/pricing changes.
9. **Minimal scope** — this is a focused commerce app; avoid introducing unrelated packages or patterns.

---

## 20. Quick file index

| Need to change… | Start here |
|-----------------|------------|
| Store homepage | `StorefrontController@home`, `resources/views/store/home.blade.php` |
| Product pricing rules | `SurchargeOnBase`, `StoreCart::resolved()` |
| Checkout validation | `CheckoutController@store` |
| Order persistence | `CreateOrderAction` |
| Invoice PDF | `GenerateInvoicePdfAction`, `resources/views/pdf/invoice.blade.php` |
| Admin product form | `app/Filament/Resources/Products/Schemas/ProductForm.php` |
| Routes | `routes/web.php`, `routes/api.php` |
| Scheduled jobs | `routes/console.php` |
| Service bindings | `app/Providers/AppServiceProvider.php` |
| Admin/customer panels | `AdminPanelProvider`, `AccountPanelProvider` |

---

*Document maintained with codebase. Major commerce features (Phases A–E) documented as of 2026-05-20.*
