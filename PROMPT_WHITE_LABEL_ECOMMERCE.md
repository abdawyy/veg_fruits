# Build prompt — white-label ecommerce (any business)

Copy everything below the line into a new agent session when you want the same store rebuilt for a different business. Replace the bracketed business profile first. Do not copy AL-DAWY produce wording into the new store unless that business actually sells produce.

---

## Business profile (fill this in)

- **Brand name:** [e.g. AL-DAWY / North Bakery / City Pharmacy]
- **What it sells:** [physical goods — any catalog: food, retail, services-as-products, bundles]
- **Country / currency:** [Egypt / EGP] (Paymob Visa assumes Egypt + EGP unless you change the gateway config)
- **Locales:** English (`en`) and Arabic (`ar`), RTL for Arabic
- **Units of sale:** [kg, piece, pack, hour, or a configurable unit per product]
- **Payments required:** cash on delivery **and** Paymob Visa (card)
- **Admin URL:** `/admin` · **Customer account URL:** `/my`

---

## What you are building

Build a production-ready **Laravel monolith ecommerce** that any retail business can run by changing brand settings, catalog, colors, and images — without a code deploy for look-and-feel.

Match the architecture of this repository (`aldawy/storefront`): public storefront, Filament admin, Filament customer account, Sanctum API, queued invoice/email/SMS, bilingual CMS, session cart, coupons, shipping cities, optional bundles and subscriptions, visitor and customer analytics.

Then add the capabilities this codebase does **not** fully have yet:

1. **Paymob Visa** — real card checkout (not a “pending until staff confirms” placeholder).
2. **Customer analysis** — store-wide dashboards plus a per-customer insight page.
3. **Design images** — admin-managed images for logo, favicon, hero, banners, empty states, category art, and Open Graph, applied on the live site.
4. **Whole-site colors** — admin color pickers that recolor the storefront and both Filament panels.
5. **Roles and permissions** — Filament Shield + Spatie, with seeded job roles and a theme/design role.

Keep money in decimal BCMath. Never use floats for prices.

---

## Stack (do not downgrade)

| Layer | Use |
|---|---|
| PHP | 8.3+ |
| Framework | Laravel 13 |
| Admin + account UI | Filament 5.6, two panels |
| Permissions | `bezhansalleh/filament-shield` ^4.2 + Spatie Permission |
| Store interactivity | Livewire 4.3 |
| CSS / JS | Tailwind CSS 4, Vite 7, Alpine.js 3 |
| i18n fields | `spatie/laravel-translatable` |
| API auth | Laravel Sanctum |
| PDF | `mpdf/mpdf` with UTF-8 / Arabic |
| Excel | `maatwebsite/excel` |
| DB | PostgreSQL in production; SQLite acceptable for tests |
| Cache | Redis |
| Session + queue | Database drivers |

---

## Architecture rules

Follow these patterns everywhere:

| Pattern | Use for |
|---|---|
| `app/Actions/*` | Writes that create or change orders, invoices, subscriptions, coupons |
| `app/Services/*` | Cart totals, pricing, money math |
| `app/DTO/*` | Structured inputs (`CreateOrderPayload`, cart lines) |
| `app/Support/*` | Session cart, CMS helper, theme resolver, analytics |
| Contracts + container bindings | Payment gateways and SMS senders |
| Domain events | `OrderCreated` → queued listener for PDF, email, SMS |
| Policies | Every Filament resource; Shield generates permission names |

**Money**

- Scale from config, default **4** (`decimal(14,4)` columns).
- All arithmetic through a `DecimalMath` service using `bc*` functions.
- Currency label from config (default `EGP`).
- Paymob amounts are **integer cents** (piasters). Convert only at the gateway boundary: `amount_cents = round(decimal_total × 100)` using BCMath, then store both the decimal total and the exact cents sent.

**Per-line pricing**

```
line_base       = unit_price × quantity
services_fee    = sum of optional service surcharges on line_base
after_services  = line_base + services_fee
packaging_fee   = optional packaging surcharge on after_services
line_subtotal   = after_services + packaging_fee
```

Surcharges are either a fixed amount or a percent. Order total:

```
subtotal − coupon_discount + order_packaging_fee + city_shipping_fee
```

Snapshot product name, unit price, services, and packaging onto each order item so later catalog edits do not rewrite history.

**Cart**

- Session keys for cart lines and coupon code.
- Line kinds: `product` (unit + quantity + optional service ids + optional packaging id) and `bundle` (curated box/kit, quantity).
- Merge identical lines. Drop inactive, out-of-stock, or invalid-unit lines when resolving.
- Guest checkout allowed (`orders.user_id` nullable). Link guest orders to the user on login/register when phone or email matches.

---

## Domain (generic, not produce-only)

Name things for any catalog. A produce shop is just seed data.

| Concept | Table / model | Notes |
|---|---|---|
| Category | `categories` | Translatable name + slug, optional image, sort, active |
| Product | `products` | SKU, translatable name/description, `price_per_unit`, optional second unit price, `unit` (`kg`, `piece`, `pack`, …), stock flag + quantity, gallery |
| Optional add-on | `preparation_services` (label in admin: **Services**) | Surcharge fixed or percent; pivot per product |
| Optional packaging | `packaging_types` | Same surcharge model; pivot per product |
| Bundle | `bundles` + `bundle_items` | Curated kit; one-time add-to-cart; optional subscription |
| Coupon | `coupons` | Percent or fixed, usage limit, date range |
| City / zone | `cities` | Translatable name, `shipping_fee` |
| Order | `orders` | Public reference `XX-XXXXXXXXXX`, status, fees, coupon, shipping address, invoice path, payment fields below |
| Order item | `order_items` | Snapshots only |
| Subscription | `subscriptions` | Recurring bundle orders; daily command creates due orders |
| User | `users` | `is_admin`, phone, default city + address, Spatie roles |
| CMS string | `content_strings` | Key → en/ar, cached |
| SEO | `seo_settings` | Global + per-route meta and OG image |
| Home banner | `home_banners` | Schedule, translatable copy, gradient, image, multiple CTA buttons |
| Design media | `design_assets` | **New.** Keyed images for chrome (see below) |
| Theme | `site_themes` | **New.** Singleton color + brand settings |
| Visitor | `site_visitors` | Hashed IP and user agent, device, UTM, country/region/city |
| Page view | `site_page_views` | Path + timestamp, skip admin/api/assets |
| Product view | `product_views` | Session, user, product, `visited_at` |
| Payment attempt | `payment_transactions` | **New.** Paymob attempts, HMAC-verified |

**Order status:** `pending` → `confirmed` → `processing` → `shipped` → `delivered`, or `cancelled`.

**Payment status (separate column):** `unpaid` | `pending` | `paid` | `failed` | `refunded`.

- COD orders: `payment_status = unpaid` until staff marks collected, or leave unpaid and treat delivery as collection — document the choice and keep it consistent. Default: COD stays `unpaid` and order status moves independently.
- Paymob Visa: order stays `pending` and `payment_status = pending` until the **server webhook** verifies HMAC. Browser return must not mark the order paid.

---

## Surfaces

### Storefront

| Route | Purpose |
|---|---|
| `GET /` | Home: hero from design assets, category tiles, featured products, banner carousel |
| `GET /shop` | Catalog, sidebar filters, search |
| `GET /categories/{category}` | Category grid |
| `GET /products/{product}` | Product page, gallery, services, packaging, live price estimate |
| `GET /bundles`, `GET /bundles/{bundle}` | Bundles; subscribe requires auth |
| Cart add/update/remove/coupon | AJAX add; toast; cart count |
| `POST /checkout` | Validate city, address, phone, payment method |
| `GET /checkout/thanks` | Thanks; Paymob return lands here only as a status hint |
| `POST /payments/paymob/webhook` | HMAC webhook, no CSRF, no auth |
| Auth | Email/password, password reset, optional phone OTP |
| Invoice download | Signed URL, rate limited, owner or guest identity check |

Locale: `?locale=en|ar` plus a visible switcher. Persist in session.

Layout: sticky header (logo from design assets, one Livewire search bar only, cart, nav, mobile drawer), footer, flash toasts, trust strip. Light/dark toggle may remain, but **brand colors come from the database theme**, not hardcoded greens.

### Admin (`/admin`)

Filament Shield plugin. Access only if `is_admin` **and** the user has at least one role. `super_admin` bypasses per-resource checks via Shield.

Resources: categories, products, bundles, coupons, orders, cities, services, packaging, users, content strings, SEO, home banners, site visitors (read-only), **design assets**, **site theme**, **payment transactions** (read-only).

Dashboards: Sales overview and Customers & traffic (details below). In-app staff guide page.

### Customer account (`/my`)

Any authenticated user. Profile, order list/detail, invoice download, cancel while `pending` or `confirmed` **only if payment is not `paid`**. If a Visa payment is `paid`, cancellation creates a staff task or note — do not auto-refund in v1.

### API (`/api/v1`)

Public: categories, products, product show, cart quote, create order (lines in the body, not the session cart).

Sanctum: `GET /api/user`, `GET /api/orders`.

---

## Feature 1 — Paymob Visa

Replace the placeholder “online pending” gateway. Keep COD. Checkout offers:

- `cod` — always
- `paymob_card` — only when `PAYMOB_ENABLED=true` and API key, integration id, HMAC secret, and public key are set

### Config (`config/payments.php`)

```
PAYMOB_ENABLED=false
PAYMOB_API_KEY=
PAYMOB_SECRET_KEY=
PAYMOB_PUBLIC_KEY=
PAYMOB_CARD_INTEGRATION_ID=     # Visa / Mastercard card integration id from Paymob dashboard
PAYMOB_HMAC_SECRET=
PAYMOB_CURRENCY=EGP
PAYMOB_BASE_URL=https://accept.paymob.com
PAYMOB_NOTIFICATION_URL=        # absolute URL to /payments/paymob/webhook
PAYMOB_REDIRECTION_URL=         # absolute URL to /checkout/paymob/return
```

Never log API keys, HMAC secret, or full card PANs. You may store masked PAN (`source_data.pan`) after a verified webhook.

### Flow (Paymob Intention API — preferred)

Implement `PaymobCardGateway` behind `PaymentGatewayInterface`.

1. `CreateOrderAction` persists the order inside a DB transaction with `payment_gateway = paymob_card`, `payment_status = pending`, `status = pending`.
2. After commit, call Paymob **only if** the chosen gateway is `paymob_card`.
3. `POST {BASE}/v1/intention/` with `Authorization: Token {SECRET_KEY}`:
   - `amount`: integer cents matching the order total
   - `currency`: `EGP`
   - `payment_methods`: `[CARD_INTEGRATION_ID]` (this is the Visa/card integration — do not send wallet ids)
   - `items`: name, amount (cents), quantity, description
   - `billing_data`: first/last name, email, phone (`+20…`), country `EG`, city, street, and the required non-empty Paymob placeholders (`apartment`, `floor`, `building`, `postal_code`, `state`) filled from the address or `"NA"`
   - `special_reference`: the order’s public reference (unique)
   - `notification_url`, `redirection_url`
4. Save `payment_transactions` row: order id, `intention_id`, `client_secret`, amount cents, status `pending`.
5. Redirect the browser to  
   `https://accept.paymob.com/unifiedcheckout/?publicKey={PUBLIC_KEY}&clientSecret={CLIENT_SECRET}`  
   so the customer pays by **Visa / card** on Paymob’s hosted page. Do not build a custom card form and do not collect PAN, CVV, or expiry in this app.
6. On gateway HTTP failure: keep the order, set the transaction to `failed`, show a retry action that creates a **new** intention for the same unpaid order (new transaction row). Do not create a second order.

### Webhook (source of truth)

`POST /payments/paymob/webhook`:

- Disable CSRF for this path only.
- Read Paymob’s transaction object and `hmac` query/body field.
- Compute HMAC-SHA512 with `PAYMOB_HMAC_SECRET` over the documented field concatenation, in this order, stringified exactly as Paymob sends them (booleans as `true`/`false`):

```
amount_cents
created_at
currency
error_occured
has_parent_transaction
id
integration_id
is_3d_secure
is_auth
is_capture
is_refunded
is_standalone_payment
is_voided
order.id
owner
pending
source_data.pan
source_data.sub_type
source_data.type
success
```

- Reject (HTTP 401, no state change) when HMAC mismatches.
- Find the local order by `special_reference` / merchant order id stored on the transaction. If amounts differ, do not mark paid; flag `amount_mismatch`.
- Idempotent on Paymob transaction id: a duplicate success webhook must not send a second email or second invoice.
- `success === true` and `pending === false` and not voided/refunded → `payment_status = paid`, store transaction id, masked PAN, `paid_at`. Then dispatch the same paid-confirmation side effects once (invoice if not already generated, customer email/SMS, admin email).
- `success === false` → `payment_status = failed`. Order remains `pending` so the customer can retry.
- `is_refunded` / `is_voided` → `payment_status = refunded` and append an order note. Do not call Paymob’s refund API in v1.

### Browser return

`GET /checkout/paymob/return` only shows “payment received, confirming…” or “payment failed”. It may poll the local order for up to a few seconds. It must **not** set `paid` by itself.

### COD

`CashOnDeliveryGateway` leaves `payment_status = unpaid` and does not call Paymob. Checkout validation rejects unknown gateway ids.

### Tests

- HMAC accept and reject (fixture payload, no live HTTP).
- Amount mismatch does not mark paid.
- Duplicate webhook is idempotent.
- Disabled Paymob hides the Visa option.
- Cents conversion: `10.5000` EGP → `1050` cents; `10.5050` follows the documented rounding rule (half away from zero on the cents place) and that rule is unit-tested.

---

## Feature 2 — Customer analysis

### Tracking (already the right model — keep it)

Middleware on the web stack, after the response when possible:

- Upsert `site_visitors` by session id: first/last seen, hashed IP, hashed user agent, device type, referrer, UTM, optional user id when logged in.
- Insert `site_page_views` for storefront HTML only (skip `/admin`, `/my`, `/api`, assets).
- Insert `product_views` on the product page.
- Geo: prefer Cloudflare country/city headers; otherwise one HTTP lookup per new session (`ANALYTICS_GEOIP_*`), stored as country code, country, region, city, `geo_resolved_at`. Never store raw IP.

Timezone for charts: `ANALYTICS_TIMEZONE` (default `Africa/Cairo`).

Optional public tags, env-gated, empty by default: GA4 measurement id, Meta pixel id.

### Sales dashboard (`/admin`)

Period filter stored in session: today, 7, 30, 90, 365 days. Each KPI shows change versus the previous window (today compares to yesterday-to-now). Cancelled orders are excluded from revenue.

Widgets:

- Revenue, order count, average order value, pending orders
- Revenue bars + order line, bucketed hourly / daily / monthly by period length
- Orders by status
- Orders and revenue by delivery city (top 10)
- Top products by line revenue
- Top products by views
- Latest orders table (row opens the order)

### Traffic dashboard (`/admin/traffic`)

- Active visitors (last 5 minutes), sessions, page views, conversion (orders ÷ sessions), mobile share
- Page views, product views, and orders by hour and by weekday
- Sessions by city / region / country
- Direct vs referrer hosts
- Top storefront paths

### Per-customer insights (user view page)

For the linked user, from `CustomerInsights` (memoize per request):

- Locations (top 3) and devices
- Session count, first seen, last seen
- Usual browsing hour and usual purchase hour
- Most viewed products, most purchased products, recent purchases
- Orders, total spent, average order, first/last order, cancelled count
- Activity chart (views vs orders over time)

Customer list columns: total spent, last order, last location, last visit.

Export: visitors and page views to Excel. Analytics viewer role can open these pages and cannot edit catalog or orders.

---

## Feature 3 — Design images

Admins change the look by uploading images. No deploy.

### `design_assets`

| Column | Purpose |
|---|---|
| `key` | Unique slug: `logo`, `logo_dark`, `favicon`, `hero`, `og_default`, `empty_cart`, `empty_shop`, `auth_side`, `footer_mark` |
| `image_path` | Public disk path |
| `alt` | Translatable JSON |
| `is_active` | |

Uploading a new file deletes the previous file on the public disk. `php artisan storage:link` is required. URLs must be absolute on the current host so Filament image columns and Open Graph tags work.

Seed sensible SVG or generated placeholders so a fresh install is not broken before uploads.

### Where each key is used

- `logo` / `logo_dark`: navbar, admin brand logo, invoice PDF header, emails
- `favicon`: `<link rel="icon">`
- `hero`: home hero media beside the banner carousel
- `og_default`: fallback `og:image` when a page has no SEO image
- `empty_cart`, `empty_shop`: empty states
- `auth_side`: login / register panel
- `footer_mark`: footer badge

### Also support images that already exist in this style of app

- **Product:** uploaded `image_path` wins over external `image_url`. Add a **gallery** (`product_images` ordered paths). Storefront shows the gallery; cart and cards use the cover.
- **Category:** optional cover image on tiles.
- **Home banners:** image upload or external URL, schedule (`starts_at` / `ends_at`), sort order, translatable title, subtitle, badge, gradient stops, and a repeater of CTA buttons (label + URL). Deleting a banner deletes its file.
- **SEO record:** optional OG image upload per route.

Admin copy for staff: recommended sizes (logo wide transparent PNG, hero 1600×900, banner 1600×700, favicon 64 or 128 PNG, OG 1200×630). Validate mime (`jpg`, `png`, `webp`, `svg` for logo only) and max size (e.g. 4 MB).

Filament resource: **Design → Images**, grouped by key, preview, replace, alt text in EN and AR.

---

## Feature 4 — Change the colors of the whole website

One singleton `site_themes` row, edited at **Design → Colors**. Saving updates the storefront and both Filament panels on the next request (cache the row; forget the cache on save).

### Fields

| Field | Default | Used for |
|---|---|---|
| `brand_name` | from `APP_NAME` | Navbar wordmark, Filament brand, invoice, `<title>` prefix |
| `primary` | `#16a34a` | Buttons, links, active nav, Filament primary |
| `primary_dark` | `#14532d` | Hover, footer band, hero gradient start |
| `primary_light` | `#4ade80` | Dark-mode accents |
| `accent` | `#f97316` | Kickers, badges, secondary CTA |
| `accent_light` | `#fdba74` | Soft accent backgrounds |
| `canvas` | `#f8fafc` | Page background |
| `surface` | `#f1f5f9` | Cards / strips |
| `border` | `#e2e8f0` | Hairlines |
| `success` | `#10b981` | Success toasts |
| `danger` | `#ef4444` | Errors |
| `theme_color` | same as primary | `<meta name="theme-color">` |

Validate every color as `#RRGGBB`. Reject values that fail contrast of primary text on white below WCAG AA for normal text (ratio 4.5) and show the admin a warning rather than saving an unreadable primary — still allow save after an explicit “use anyway” toggle so a brand color is not blocked forever.

### How colors reach the UI

Do **not** hardcode brand hex in Blade or in Filament `Color::hex('#16a34a')`.

1. Storefront layout prints a `<style>` block that sets Tailwind v4 tokens at `:root` (and overrides under `html.dark` for canvas/surface/border only; brand hues stay):

```css
:root {
  --color-brand: {{ $theme->primary }};
  --color-brand-dark: {{ $theme->primary_dark }};
  --color-brand-light: {{ $theme->primary_light }};
  --color-accent: {{ $theme->accent }};
  /* canvas, surface, border, success, danger */
}
```

2. All existing utility classes (`.aldawy-btn-primary`, cards, heroes, nav, footer, toasts) already consume those variables. Rename the prefix to a neutral `.store-*` **or** keep a prefix, but every colored rule must use the variables. Shadows that tint with the brand must use `color-mix` or the primary RGB components so a new color recolours the shadow too.
3. Filament admin and account panels: resolve `->colors(['primary' => Color::hex($theme->primary)])` and `->brandName()` / `->brandLogo()` from the theme and design assets inside the panel provider (load the cached theme; fall back to defaults if the table is missing during migrate).
4. Invoice PDF and email headers use `primary` and the logo asset.
5. Admin page shows a live swatch row: primary button, accent badge, nav sample, card on canvas. No separate frontend build step.

Seed the green/orange defaults so the first paint matches a fresh grocery brand, then let any business replace them.

---

## Feature 5 — Roles and permissions

Packages: Spatie Permission + Filament Shield. Generate permissions for the admin panel (`shield:generate --all --panel=admin`).

**Gate to enter `/admin`:** `users.is_admin = true` AND at least one role. Customers never get `is_admin`. `/my` is any logged-in user and does not use Shield resources.

**Seeded roles** (`AdminRolesSeeder`), guard `web`:

| Role | Can |
|---|---|
| `super_admin` | Every permission. Only this role may assign `super_admin` or edit roles. |
| `orders_manager` | Orders (full), view users, sales dashboard widgets |
| `catalog_manager` | Products, categories, bundles, services, packaging, cities, coupons |
| `content_manager` | Banners, CMS strings, SEO |
| `design_manager` | Design assets, site theme, banners (so marketing can recolor and replace images without catalog access) |
| `analytics_viewer` | View dashboards, chart widgets, site visitors, admin guide. No create/update/delete |
| `finance_viewer` | View orders and payment transactions, no status edits, no catalog |

Assign roles on the user edit page. Ship a policies folder: one policy per resource, including `DesignAssetPolicy`, `SiteThemePolicy`, `PaymentTransactionPolicy`, `RolePolicy`.

Shield UI stays at **Admin → Roles** for custom roles beyond the seed. Document the six starter roles on the in-app admin guide in English and Arabic.

Default seeded admin: `admin@example.test` / `password` with `is_admin` and `super_admin`. Print this only in local seed output and `.env.example` comments — not in the storefront.

---

## Localization and CMS

- Models with public names use Spatie translatable JSON (`en`, `ar`).
- Storefront chrome strings live in `lang/en/store.php` and `lang/ar/store.php` (both files required for every new key).
- Editable marketing lines override lang via `content_strings` (`Cms::text($key, $fallback)`), cached forever, cache busted on save.
- Filament labels go through Laravel `__()` with lang files, not hardcoded English only.

---

## Checkout, invoices, notifications

1. Validate cart, city, phone, address, payment method, stock.
2. Idempotency: one-time checkout nonce plus an in-flight session lock so a double submit cannot create two orders.
3. `CreateOrderAction` in a transaction: totals, stock decrement when tracked, order + items, coupon usage, gateway identifier.
4. COD: thank-you page immediately, then queued listener.
5. Visa: redirect to Paymob; listener runs when webhook marks `paid` (still generate a proforma/invoice PDF at order creation so COD and card share one code path — card email waits until paid).
6. Queued listener: PDF (mPDF, Arabic-capable font), signed download URL (default 14 days), SMS (`log` or `http` driver), customer mail, admin mail.
7. Status changes queue a localized “order status changed” mail.
8. Throttle cart and checkout routes.

---

## Subscriptions and bundles

- Bundle detail can add the kit to the cart or, when logged in, start a subscription (weekly or monthly).
- First subscription order is created immediately.
- `store:process-subscriptions` daily at 06:00 creates the next order and advances `next_order_at`.
- Renewal orders use COD unless the customer has a stored choice — **do not** charge a saved Visa token in v1. Renewal orders are COD or `pending` staff follow-up. Document that on the subscribe form.

---

## Security

- CSRF on all web POSTs except the Paymob webhook.
- HMAC on every Paymob notification before any write.
- Hashed IP and user agent only.
- Invoice authorization: signed URL or authenticated owner; guests must match the phone/email stored on the order.
- Policies on every admin query. Never `Model::query()` in Filament without the resource authorization.
- Mass assignment: explicit `$fillable` / attribute fillable lists. Payment status is not customer-fillable.
- Secrets only in `.env`. `.env.example` lists keys with empty values.

---

## Tests you must add

Feature tests, SQLite:

- Cart AJAX add, coupon, stock rejection
- Checkout COD creates order, queues listener (fake queue)
- Checkout Visa with `Http::fake()` creates a pending order and redirects to Paymob’s unified checkout URL
- Webhook HMAC success, failure, mismatch, replay
- Theme save changes the CSS variables rendered on `/`
- Design asset `logo` URL appears in the navbar
- `analytics_viewer` can open traffic dashboard and receives 403 on product create
- `design_manager` can update theme and cannot update an order
- Customer insights page renders for a user with views and orders
- Invoice download forbidden for a different user

Run `php artisan test` before calling the work done.

---

## Implementation order

1. Laravel app, panels, auth, theme singleton with seeded colors, design asset placeholders, Shield, seeded roles.
2. Catalog, cart, pricing, cities, coupons, COD checkout, invoices.
3. Storefront Blade using CSS variables only.
4. Paymob gateway, transactions table, webhook, return page.
5. Visitor middleware, both dashboards, per-customer insights.
6. Banner + gallery + design asset admin, wire every key into Blade.
7. Excel import/export for products and orders, phone OTP if enabled, subscriptions.
8. EN/AR strings, admin guide, seed data for the business profile above.

---

## Done when

- A non-developer admin can set brand name, logo, favicon, hero, banner images, and the full color palette, reload `/`, and see the new brand without a rebuild.
- Checkout can place a COD order and a Visa order; Visa becomes `paid` only after a valid Paymob HMAC webhook; the card UI is Paymob hosted checkout using the card integration id.
- Sales and traffic dashboards filter by period; a customer’s admin page shows location, device, browse/purchase timing, and top products.
- `super_admin`, `orders_manager`, `catalog_manager`, `content_manager`, `design_manager`, `analytics_viewer`, and `finance_viewer` exist and are enforced by policies.
- Arabic RTL storefront works for nav, product page, cart, and checkout.
- `php artisan test` passes.

Do not invent a second frontend SPA. Do not collect card numbers. Do not mark Visa orders paid from the browser redirect.
