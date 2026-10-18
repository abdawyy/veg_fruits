# AL-DAWY — Changelog

All notable modifications, newest first. Each entry lists **what changed**, **files**, and **what to run after pulling**.

---

## 2026-10-19 — Home banner: one button, many products

### What to run after pulling

```powershell
php artisan migrate
php artisan view:clear
```

### What changed

A home banner still has **one** button (English + Arabic labels). That button can target **many products** from any category via a multi-select. One product → product page. Many products → `/shop?products=12,37` (filtered list). Those products also appear as the banner product cards. Old single-link banners keep working.

### Files

- New: `database/migrations/2026_10_19_000001_add_cta_buttons_to_home_banners_table.php`, `tests/Feature/HomeBannerMultipleCtaButtonsTest.php`
- `app/Models/HomeBanner.php`, `app/Support/HomeBannerAttributes.php`
- `app/Filament/Resources/HomeBanners/Schemas/HomeBannerForm.php`
- `app/Filament/Resources/HomeBanners/Pages/{Create,Edit}HomeBanner.php`
- `app/Http/Controllers/StorefrontController.php`, `resources/views/store/{partials/banners,shop}.blade.php`
- `lang/{en,ar}/aldawy.php`

---

## 2026-10-10 — Admin dashboard redesign (UI/UX)

### What to run after pulling

```powershell
php artisan optimize:clear
php artisan shield:generate --all --panel=admin   # registers permissions for the new pages/widgets
php artisan db:seed --class=AdminRolesSeeder      # refreshes role → permission mapping
```

No migrations.

### What changed

The single long dashboard (duplicate KPI cards, each chart with its own date range) is replaced by **two focused pages** that share one **Period** switch (Today · 7 days · 30 days · 90 days · 12 months). Every card and chart on the page follows it, and the choice is remembered for the session.

- **Sales overview** (`/admin`, first in the menu)
  - KPI cards: revenue, orders, average order value — each with % change vs the previous period (vs yesterday-to-now for Today), green/red trend arrow and sparkline; pending orders card turns amber and links to the orders list.
  - Revenue & orders trend (hourly for Today, daily, monthly for 12 months).
  - Orders by status (doughnut), orders by delivery area, best sellers by revenue, most viewed products (now per period from `product_views`, not the all-time counter).
  - Latest orders table — click a row to open the order.
- **Customers & traffic** (`/admin/traffic`)
  - KPI cards: visitors right now (refreshes every 30s), sessions, page views, conversion rate, mobile share.
  - When customers visit & buy (by hour, Cairo time), busiest days, visitor locations (city / region / country), traffic sources (doughnut incl. direct), top shop pages.
- Layout: 1 column on phones, 2-column chart grid on desktop, consistent chart heights, legends at the bottom, combined tooltips.
- Removed: Filament account/welcome card and the old `SalesOverviewStats`, `CommerceSnapshotWidget`, `ActiveVisitorsWidget`, `OrdersOverTimeChart` widgets (their numbers live in the new KPI cards).

### Files

- New: `app/Filament/Pages/Dashboard.php`, `app/Filament/Pages/TrafficDashboard.php`, `app/Filament/Widgets/{SalesKpiStats,TrafficKpiStats,SalesTrendChart,OrderStatusChart,TopSellingProductsChart,LatestOrdersTable}.php`, `app/Filament/Widgets/Concerns/{HasDashboardPeriod,BuildsKpiStats}.php`, `app/Support/Analytics/DashboardPeriod.php`, `tests/Feature/AdminDashboardTest.php`
- Updated to the shared period + half-width layout: `ActivityPatternChart`, `ActivityByHourChart`, `ActivityByWeekdayChart`, `OrdersByCityChart`, `TopProductsByViewsChart`, `VisitorLocationsChart`, `TrafficReferrersChart`, `TopStorePathsChart`
- `app/Support/Analytics/TimeBuckets.php` — `series()` for continuous trend buckets in the reporting timezone
- `app/Providers/Filament/AdminPanelProvider.php` — custom dashboards discovered; default dashboard and account widget removed
- `database/seeders/AdminRolesSeeder.php`, `app/Support/AdminRolePermissions.php` — permission names for the new widgets/pages
- `resources/views/filament/pages/admin-guide.blade.php`, `PROJECT_ANALYSIS.md` — docs

---

## 2026-10-10 — Customer analytics + admin product image fix

### What to run after pulling

```powershell
php artisan migrate          # adds visitor location columns
php artisan storage:link     # required once per machine/server — uploaded images are served from public/storage
php artisan config:clear
```

Optional `.env` (defaults shown — already added to `.env.example`):

```env
ANALYTICS_TIMEZONE=Africa/Cairo
ANALYTICS_GEOIP_ENABLED=true
ANALYTICS_GEOIP_URL=https://ipwho.is/{ip}
ANALYTICS_GEOIP_TIMEOUT=2
# FILESYSTEM_PUBLIC_URL=/storage
```

---

### 1. Fix — uploaded product images not showing in the admin

**Symptom:** after uploading a product image, the storefront showed an image, but the admin **products table** and the **product edit page** showed nothing.

**Root causes**

| # | Cause | Effect |
|---|-------|--------|
| 1 | `Product::display_image_url` returned a *relative* path (`/storage/products/x.jpg`). Filament's `ImageColumn` only uses a value as-is when it is a full URL; otherwise it re-resolves it against the default disk (`local`, private), producing a broken link. | Empty image in the products table. |
| 2 | The public disk URL was built from `APP_URL` (`http://127.0.0.1:8000/storage`). The upload field loads its preview from that URL, so opening the admin on any other host (`localhost`, a Laragon `.test` domain, the real domain) blocked the preview as cross-origin. | Empty preview on the product edit page. |
| 3 | `public/storage` symlink was missing on this machine. Laravel 13's private `local` disk also serves `/storage/...` but requires a signature, so unsigned requests got **403**. | Uploaded files not reachable at all. |
| 4 | `display_image_url` preferred the external `image_url` over the uploaded file — the opposite of the admin hint *"upload is shown first on the shop"*. Seeded products all have a stock-photo URL, so a new upload never replaced it on the shop. | Upload ignored on the storefront. |

**Changes**

| File | Change |
|------|--------|
| `app/Models/Product.php` | `display_image_url` now returns the **uploaded file first**, as an **absolute** URL for the current host (`url(Storage::disk('public')->url(...))`), falling back to `image_url`. |
| `config/filesystems.php` | Public disk `url` is now host-relative: `env('FILESYSTEM_PUBLIC_URL', '/storage')`. Previews work on any hostname. |
| `app/Filament/Resources/Products/Schemas/ProductForm.php` | New **"Current image (as shown on the shop)"** preview on the edit page (works for uploads *and* external URLs). Upload field gets a 200 px preview, **Open** and **Download** buttons. |
| `public/storage` | Created with `php artisan storage:link` (Windows junction). |

---

### 2. New — visitor location ("where the shop is opened from")

Each visitor session is located **once** (country, region/governorate, city). Raw IP addresses are **never stored** (only the existing salted hash).

How a location is found, in order:

1. **Cloudflare headers** (`CF-IPCountry`, `cf-ipcity`, `cf-region`) if the site is behind Cloudflare — instant, no external call. Enable *"Add visitor location headers"* in Cloudflare → Rules → Managed Transforms for city/region.
2. Otherwise **one HTTP lookup** to `ANALYTICS_GEOIP_URL` (default `https://ipwho.is/{ip}`), run **after the page is sent** so customers never wait. Results are cached per IP for 7 days; failures back off for 1 hour. ipwho.is, ipapi.co and ip-api.com response formats are supported.
3. Private/local IPs (development) are recorded as **"Local network"**.

| File | Change |
|------|--------|
| `database/migrations/2026_10_10_000001_add_geo_location_to_site_visitors_table.php` | Adds `country_code` (indexed), `country`, `region`, `city`, `geo_resolved_at` to `site_visitors`. |
| `app/Services/Analytics/VisitorGeoLocator.php` | **New.** Header + HTTP lookup, caching, private-IP handling, country names via `intl`. |
| `app/Http/Middleware/TrackSiteVisitor.php` | Resolves location for new sessions (headers immediately, HTTP lookup after the response). |
| `app/Models/SiteVisitor.php` | New fillable/cast fields, `location_label` accessor ("Cairo, Cairo Governorate, Egypt"). |
| `app/Filament/Resources/SiteVisitors/Tables/SiteVisitorsTable.php` | **Country** and **City** (+ region) columns, searchable/sortable; **Country** and **Device** filters. |
| `config/analytics.php` | New `timezone` and `geoip` settings. |

> **Behind a proxy / load balancer?** Configure trusted proxies so `request()->ip()` is the visitor's IP, not the proxy's — otherwise every visitor gets the server's location.

---

### 3. New — dashboard analytics widgets

All times are converted from UTC (database) to **Cairo time** (`ANALYTICS_TIMEZONE`), including daylight saving.

| Widget | Shows | Filters |
|--------|-------|---------|
| **Where visitors open the shop from** (`VisitorLocationsChart`) | Sessions by city / region / country, top 15, last 30 days | City · Region · Country |
| **When customers visit & buy (hour of day)** (`ActivityByHourChart`) | Page views, product views and orders per hour (00:00–23:00) | 7 / 30 / 90 days |
| **Busiest days of the week** (`ActivityByWeekdayChart`) | Same three series per weekday, Saturday first | 7 / 30 / 90 days |
| **Orders & revenue by delivery area** (`OrdersByCityChart`) | Order count + revenue (EGP) per delivery city; cancelled excluded | 7 / 30 / 90 days / 12 months |

Existing **Most viewed products** chart is unchanged.

| File | Change |
|------|--------|
| `app/Filament/Widgets/ActivityPatternChart.php` | **New** abstract base (shared datasets, dual axis: views left, orders right). |
| `app/Filament/Widgets/ActivityByHourChart.php` | **New.** |
| `app/Filament/Widgets/ActivityByWeekdayChart.php` | **New.** |
| `app/Filament/Widgets/VisitorLocationsChart.php` | **New.** |
| `app/Filament/Widgets/OrdersByCityChart.php` | **New.** |
| `app/Support/Analytics/TimeBuckets.php` | **New.** Groups timestamps per stored hour in SQL (MySQL, PostgreSQL, SQLite, SQL Server), then shifts each bucket to the reporting timezone — accurate and cheap on large tables. |

---

### 4. New — per-customer analysis

**Admin → Customers → Users → (view a user)** now shows two new sections and a chart:

**Customer insights — visits**
- Opens the shop from (top locations with session counts)
- Devices (mobile / desktop / tablet)
- Sessions, first visit, last visit
- **Usually browses** — busiest weekday + hour, e.g. *"Thursday, 20:00–21:00 (Africa/Cairo)"*
- **Most viewed products** — top 5 with view count and last view time

**Customer insights — purchases** (cancelled orders excluded)
- Orders, total spent, average order, first order, last order, cancelled orders
- **Usually orders** — busiest weekday + hour for purchases
- **Most purchased products** — top 5 with quantity and number of orders
- **Recent purchases** — last 5 with time, total, status and reference

**Chart: "This customer: browsing vs. buying times"** — views vs. orders by hour of day or weekday.

**Users list** gains columns: **Total spent (EGP)**, **Last order**, **Location**, **Last visit** (sortable).

| File | Change |
|------|--------|
| `app/Support/Analytics/CustomerInsights.php` | **New.** All per-customer queries, memoised per page render. |
| `app/Filament/Resources/Users/UserResource.php` | Two insight sections in the infolist; widget registered. |
| `app/Filament/Resources/Users/Widgets/CustomerActivityChart.php` | **New** (not shown on the main dashboard). |
| `app/Filament/Resources/Users/Pages/ViewUser.php` | Shows the chart under the profile. |
| `app/Filament/Resources/Users/Tables/UsersTable.php` | New aggregate columns; eager-loads latest visit. |
| `app/Models/User.php` | New relations: `siteVisitors()`, `latestSiteVisitor()`, `productViews()`. |

**Data coverage:** visit/view history is linked to a customer only while they are **logged in** (sessions before login stay anonymous). Guest orders count once the guest registers or logs in with the same email/phone (existing guest-order linking).

---

### 5. Tests

`tests/Feature/CustomerAnalyticsAndProductImagesTest.php` — **8 tests, 42 assertions, all passing**:

- uploaded image is absolute and wins over the external URL
- admin products list and edit page render the uploaded image URL
- hour/weekday buckets convert UTC → Cairo correctly (incl. day roll-over)
- GeoIP lookup parsing, caching, private-IP skip
- location stored from Cloudflare headers
- location looked up after the response
- customer insights values + admin user page render
- all four dashboard widgets render

Run (Laragon PHP has SQLite disabled by default):

```powershell
php -d extension=pdo_sqlite -d extension=sqlite3 vendor\bin\phpunit
```

Full suite: 21/22 pass. The one failure, `CheckoutTest::test_guest_can_checkout_and_notifications_fire`, was already failing before this change (it calls `followRedirects()` without the required response argument).

---

### 6. Config & docs

| File | Change |
|------|--------|
| `.env.example` | `ANALYTICS_TIMEZONE`, `ANALYTICS_GEOIP_*`, commented `FILESYSTEM_PUBLIC_URL`. |
| `.env` | Same analytics keys added locally. |
| `PROJECT_ANALYSIS.md` | Widgets, data model, image behaviour, config updated. |
| `README.md` | Links this changelog; `storage:link` added to quick start. |
| `resources/views/filament/pages/admin-guide.blade.php` | Admin guide explains the new dashboard analytics and customer insights. |
| `CHANGELOG.md` | **New** (this file). |

### Privacy note

Location is approximate (city level), derived from the IP at the time of the visit and stored without the IP. If you publish a privacy policy, mention that the site records approximate visitor location and browsing activity for analytics.
