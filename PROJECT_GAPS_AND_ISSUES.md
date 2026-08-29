# AL-DAWY — Gaps, Flow Improvements & Issues

> **Purpose:** Audit of product gaps, UX flows, and risks — with **current status** after Phases A–E and the **2026 storefront UX refresh**.  
> **Companion:** [PROJECT_ANALYSIS.md](./PROJECT_ANALYSIS.md) (architecture) · [MARKETING_GUIDE.md](./MARKETING_GUIDE.md) (customer-facing) · [TODO.md](./TODO.md) (backlog) · [docs/VERIFICATION.md](./docs/VERIFICATION.md) (code verification)

---

## Status summary

| Category | Shipped (A–E + UX refresh) | Still open |
|----------|---------------------------|------------|
| Missing features | 18/18 | Optional: PSP webhooks, import audit Filament UI |
| Flow improvements | All planned items | — |
| Storefront UX (2026) | Navbar, footer, shop, cart, toasts, product images | Minor: cart-preview drawer vs full cart page |
| High-impact bugs | 6/6 + Livewire/nav fixes | — |
| Medium-impact bugs | 6/6 | — |
| Low-impact polish | 4/4 + design system | — |

**Bottom line:** Original gap list is **resolved**. Storefront received a full visual/UX pass (Aug 2026). Remaining work is optional hardening and production configuration (SMS HTTP URL, payment provider integration).

---

## Table of contents

1. [Missing features — resolved](#1-missing-features--resolved)
2. [Flow improvements — resolved](#2-flow-improvements--resolved)
3. [Storefront UX refresh — resolved (2026-08)](#3-storefront-ux-refresh--resolved-2026-08)
4. [Bugs and risks — resolved](#4-bugs-and-risks--resolved)
5. [Remaining optional work](#5-remaining-optional-work)
6. [Roadmap (historical)](#6-roadmap-historical)

---

## 1. Missing features — resolved

| Feature | Shipped as |
|---------|------------|
| **Subscriptions / produce boxes** | `ProduceBoxResource`, `/boxes`, `SubscribeToProduceBoxAction`, `ProcessDueSubscriptionsAction` + daily cron |
| **Sell by piece** | `StoreCart` kg/piece, cart UI, checkout |
| **Phone OTP auth** | `/login/phone`, `SendPhoneOtpAction`, `LoginWithPhoneOtpAction` |
| **Real SMS** | `HttpSmsSender` + `ALDAWY_SMS_DRIVER` |
| **Online payments** | `OnlinePendingPaymentGateway` when `ALDAWY_ONLINE_PAYMENT_ENABLED` |
| **API commerce** | `GET/POST /api/v1/catalog/*`, `cart/quote`, `orders` |
| **Inventory / stock** | `track_stock`, `stock_quantity`, cart/checkout guards, out-of-stock UI |
| **Categories admin** | `CategoryResource` |
| **Produce boxes admin + storefront** | Full CRUD + cart + subscribe |
| **Password reset** | Forgot/reset routes and views |
| **Email verification** | `MustVerifyEmail`, optional enforcement |
| **Coupons** | Schema, admin, cart apply, checkout discount |
| **Customer order cancellation** | `CancelCustomerOrderAction` on `/my` |
| **Status-change notifications** | `OrderObserver` + queued mail |
| **Cart preview drawer** | Livewire `cart-preview-drawer` |
| **Guest order linking** | `LinkGuestOrdersToUserAction` on login/register |

---

## 2. Flow improvements — resolved

### 2.1 Checkout & cart

| Improvement | Resolution |
|-------------|------------|
| Estimated total on product page | `ProductEstimateController` + AJAX on product form |
| Edit line options in cart | `POST /cart/options`, `CartController::updateOptions` |
| Price change warning | `StoreCart::priceChangedLines()`, cart banner |
| Checkout idempotency | `checkout_nonce`, `checkout_in_flight`, disabled submit |
| Post-checkout account | Thanks guest nudge; order linking on auth |
| Saved addresses | `default_city_id`, address fields on `users`, checkout prefill |
| Register copy | `register_sub` updated; `storefront_coming` removed |

### 2.2 Order fulfillment

| Improvement | Resolution |
|-------------|------------|
| Async post-order work | `OnOrderCreatedGenerateInvoiceAndNotify` implements `ShouldQueue` |
| Customer invoice access | `/my` download, `/checkout/invoice`, signed email link |
| Admin/customer status labels | `OrderStatus::label()` EN/AR |
| Order-level packaging fee | [docs/PACKAGING_FEES.md](./docs/PACKAGING_FEES.md) + admin form helper |

### 2.3 Auth & account

| Improvement | Resolution |
|-------------|------------|
| Phone login | OTP flow |
| Redirect after register | `?redirect=` on register |
| Account UX | [docs/ACCOUNT_UX.md](./docs/ACCOUNT_UX.md) |

### 2.4 Operations & content

| Improvement | Resolution |
|-------------|------------|
| Subscriptions cron | Creates real orders |
| Excel import | Products: dry-run toggle, `SpreadsheetImportRunner`, `import_audit_logs` |
| CMS vs lang | [docs/CMS_AND_LANG.md](./docs/CMS_AND_LANG.md) |
| Stale lang keys | Cleaned up |

### 2.5 Observability & quality

| Improvement | Resolution |
|-------------|------------|
| Feature tests in CI | `.github/workflows/tests.yml`, no SQLite skip |
| Project README | [README.md](./README.md) with doc links |

---

## 3. Storefront UX refresh — resolved (2026-08)

Full customer-facing redesign aligned with [MARKETING_GUIDE.md](./MARKETING_GUIDE.md). All items below are **shipped**.

### 3.1 Layout & navigation

| Item | Resolution |
|------|------------|
| Redesigned navbar | `store/partials/navbar.blade.php` — trust strip, logo, single Livewire search, cart CTA, desktop links + browse dropdown, mobile drawer grid |
| Navbar Livewire error | Removed duplicate `product-search-bar` instances (one search component per page) |
| Cart navigation | Cart icon links to `/cart` (not drawer-only); mobile cart button in header |
| Redesigned footer | CTA band, 4-column links, trust badges, product count |
| Trust bar sitewide | Included on home, shop, cart, product, services, boxes, checkout thanks |

### 3.2 Pages & components

| Item | Resolution |
|------|------------|
| Shop page | Category sidebar (desktop), hero, product grid, empty states, CTA band |
| Product cards | Hover overlay, category chip, AJAX add-to-cart |
| Cart page | Card line items, sticky checkout sidebar, coupon partial |
| Product / box / services / thanks pages | Unified heroes, breadcrumbs, `aldawy-card` layouts |
| Design system | `resources/css/app.css` — `.aldawy-btn-*`, `.aldawy-card`, `.aldawy-toast-*`, `.aldawy-nav-*`, `.aldawy-footer-*` |
| EN/AR copy | New keys in `lang/en/aldawy.php` and `lang/ar/aldawy.php` |

### 3.3 Feedback & cart AJAX

| Item | Resolution |
|------|------------|
| Toast notifications | `aldawyToast()` — success/error, progress bar, dismiss, “View cart” on add |
| Session flash → toast | `flash-status.blade.php` triggers toast on page load |
| Livewire 4 cart sync | Removed `Livewire::dispatch()` from controllers; client `Livewire.dispatch('cart-updated')` in `app.js` |

### 3.4 Product images

| Item | Resolution |
|------|------------|
| External image URLs | `ProduceStockPhoto` + `image_url` on products; `display_image_url` prefers URL over local path |
| Restore command | `php artisan aldawy:restore-product-image-urls` |
| Local download (optional) | `php artisan aldawy:generate-product-images` + `ProductImageFetcher` |
| Seeder default | `ProduceCatalogSeeder` sets URLs and runs restore after seed |
| Decorative heroes | Category tiles, home collage, services/boxes heroes use `ProduceStockPhoto` URLs |

---

## 4. Bugs and risks — resolved

### 4.1 High impact (fixed)

| Issue | Fix |
|-------|-----|
| Synchronous order listener | `ShouldQueue` on listener |
| Double-submit checkout | Nonce + in-flight flag + UI |
| Inactive products in cart | Reject at add; `hasDroppedLines()` at checkout |
| Feature tests skipped | CI runs tests on SQLite |
| Livewire `dispatch()` from controller | Removed; client-side dispatch after AJAX cart add |
| Duplicate search Livewire components | Single search bar in navbar grid layout |

### 4.2 Medium impact (fixed)

| Issue | Fix |
|-------|-----|
| SMS not localized | `__('aldawy.sms_invoice_ready')` |
| Invoice download scope | `AuthorizeInvoiceDownloadAction`, shorter signed TTL, throttle — [docs/INVOICE_ACCESS.md](./docs/INVOICE_ACCESS.md) |
| Checkout UI float math | `money.js` + `MoneyCents` (integer piastres) |
| No rate limiting | `throttle:cart`, `throttle:checkout` |
| Misleading register copy | Updated |
| Unused `storefront_coming` | Removed |

### 4.3 Low impact (fixed)

| Issue | Fix |
|-------|-----|
| Raw order status in `/my` | `OrderStatus::label()` in infolist |
| Services page expectations | Rules block on services page |
| Notifications inline | Status notifications queued; order listener queued |
| Cart preview vs full cart | Drawer still mounted; primary UX is full `/cart` page + navbar cart CTA |

### 4.4 Security (unchanged awareness)

- Prep/packaging IDs filtered per product in `StoreCart::resolved()` — OK.
- Admin gated by `is_admin` — OK.
- Signed invoice URLs are secrets; logged-in users cannot use another account’s link — OK.

---

## 5. Remaining optional work

Not blockers for launch; track in [TODO.md](./TODO.md) if prioritised later.

| Item | Notes |
|------|--------|
| **Payment provider webhooks** | Online gateway is “pending confirmation” only; no Stripe/PayMob capture flow |
| **Live SMS** | Set `ALDAWY_SMS_DRIVER=http` + provider URL in production |
| **API session cart** | Mobile clients send line payloads; no server-side session cart API |
| **Import audit admin UI** | Logs in DB; optional Filament resource for staff |
| **Dry-run on all imports** | Pattern exists for products; extend to cities/orders imports |
| **Cart preview drawer** | Livewire drawer still in layout; could be removed or wired to a “quick view” action if product wants drawer-only UX again |
| **Orphan partial** | `cart-nav-icon.blade.php` superseded by inline cart in `navbar.blade.php` — safe to delete when cleaning up |
| **Product image workflow** | Choose one default ops workflow: external URLs (marketing) vs local downloads (offline/CDN) |
| **Queue worker in production** | Required for PDF/email/SMS after checkout (`php artisan queue:work`) |

---

## 6. Roadmap (historical)

Phases A–E from the original roadmap are **complete**. Use [TODO.md](./TODO.md) for any new work.

| Phase | Focus | Status |
|-------|--------|--------|
| A | Stability & trust | Done |
| B | Customer experience | Done |
| C | Business features (SMS, payments, piece, auth) | Done |
| D | Boxes & subscriptions | Done |
| E | Cart drawer, categories, coupons, stock, API, OTP | Done |
| F | Storefront UX refresh (nav, footer, shop, cart, toasts, images) | Done (2026-08) |

---

## Quick reference: files by theme

| Theme | Primary files |
|-------|----------------|
| Storefront layout | `layouts/store.blade.php`, `store/partials/navbar.blade.php`, `store/partials/footer.blade.php` |
| Design / toasts | `resources/css/app.css`, `resources/js/app.js` |
| Shop UI | `store/shop.blade.php`, `store/partials/product-card.blade.php`, `shop-sidebar.blade.php` |
| Product images | `ProduceStockPhoto.php`, `RestoreProductImageUrlsCommand.php`, `GenerateProductImagesCommand.php` |
| Cart / checkout | `StoreCart.php`, `CartController.php`, `CheckoutController.php`, `CreateOrderAction.php` |
| Post-order | `OrderCreated.php`, `OnOrderCreatedGenerateInvoiceAndNotify.php`, `OrderObserver.php` |
| Subscriptions | `ProcessDueSubscriptionsAction.php`, `SubscribeToProduceBoxAction.php` |
| Auth | `LoginController.php`, `RegisterController.php`, `PhoneAuthController.php` |
| Payments / SMS | `AppServiceProvider.php`, `PaymentGatewayResolver.php`, `HttpSmsSender.php` |
| Invoices | `AuthorizeInvoiceDownloadAction.php`, `AccountInvoiceDownloadController.php` |
| API v1 | `app/Http/Controllers/Api/V1/*`, `routes/api.php` |
| Customer account | `app/Filament/Account/Resources/MyOrders/*` |
| Docs | `docs/*.md`, `README.md` |

---

*Last updated: 2026-08-29 — Phases A–E + storefront UX refresh; see [MARKETING_GUIDE.md](./MARKETING_GUIDE.md) for customer-facing summary.*
