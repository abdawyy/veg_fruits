# AL-DAWY — Gaps, Flow Improvements & Issues

> **Purpose:** Audit of product gaps, UX flows, and risks — with **current status** after Phases A–E (2026-05-20).  
> **Companion:** [PROJECT_ANALYSIS.md](./PROJECT_ANALYSIS.md) (architecture) · [TODO.md](./TODO.md) (backlog) · [docs/VERIFICATION.md](./docs/VERIFICATION.md) (code verification)

---

## Status summary

| Category | Shipped (A–E) | Still open |
|----------|---------------|------------|
| Missing features | 18/18 | Optional: PSP webhooks, import audit Filament UI |
| Flow improvements | All planned items | — |
| High-impact bugs | 6/6 | — |
| Medium-impact bugs | 6/6 | — |
| Low-impact polish | 4/4 | — |

**Bottom line:** The original gap list is largely **resolved**. What remains is optional hardening and production configuration (SMS HTTP URL, payment provider integration).

---

## Table of contents

1. [Missing features — resolved](#1-missing-features--resolved)
2. [Flow improvements — resolved](#2-flow-improvements--resolved)
3. [Bugs and risks — resolved](#3-bugs-and-risks--resolved)
4. [Remaining optional work](#4-remaining-optional-work)
5. [Roadmap (historical)](#5-roadmap-historical)

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

## 3. Bugs and risks — resolved

### 3.1 High impact (fixed)

| Issue | Fix |
|-------|-----|
| Synchronous order listener | `ShouldQueue` on listener |
| Double-submit checkout | Nonce + in-flight flag + UI |
| Inactive products in cart | Reject at add; `hasDroppedLines()` at checkout |
| Feature tests skipped | CI runs tests on SQLite |

### 3.2 Medium impact (fixed)

| Issue | Fix |
|-------|-----|
| SMS not localized | `__('aldawy.sms_invoice_ready')` |
| Invoice download scope | `AuthorizeInvoiceDownloadAction`, shorter signed TTL, throttle — [docs/INVOICE_ACCESS.md](./docs/INVOICE_ACCESS.md) |
| Checkout UI float math | `money.js` + `MoneyCents` (integer piastres) |
| No rate limiting | `throttle:cart`, `throttle:checkout` |
| Misleading register copy | Updated |
| Unused `storefront_coming` | Removed |

### 3.3 Low impact (fixed)

| Issue | Fix |
|-------|-----|
| Raw order status in `/my` | `OrderStatus::label()` in infolist |
| Services page expectations | Rules block on services page |
| Notifications inline | Status notifications queued; order listener queued |
| Cart preview unused | Drawer wired |

### 3.4 Security (unchanged awareness)

- Prep/packaging IDs filtered per product in `StoreCart::resolved()` — OK.
- Admin gated by `is_admin` — OK.
- Signed invoice URLs are secrets; logged-in users cannot use another account’s link — OK.

---

## 4. Remaining optional work

Not blockers for launch; track in [TODO.md](./TODO.md) if prioritised later.

| Item | Notes |
|------|--------|
| **Payment provider webhooks** | Online gateway is “pending confirmation” only; no Stripe/PayMob capture flow |
| **Live SMS** | Set `ALDAWY_SMS_DRIVER=http` + provider URL in production |
| **API session cart** | Mobile clients send line payloads; no server-side session cart API |
| **Import audit admin UI** | Logs in DB; optional Filament resource for staff |
| **Dry-run on all imports** | Pattern exists for products; extend to cities/orders imports |
| **Queue worker in production** | Required for PDF/email/SMS after checkout (`php artisan queue:work`) |

---

## 5. Roadmap (historical)

Phases A–E from the original roadmap are **complete**. Use [TODO.md](./TODO.md) for any new work.

| Phase | Focus | Status |
|-------|--------|--------|
| A | Stability & trust | Done |
| B | Customer experience | Done |
| C | Business features (SMS, payments, piece, auth) | Done |
| D | Boxes & subscriptions | Done |
| E | Cart drawer, categories, coupons, stock, API, OTP | Done |

---

## Quick reference: files by theme

| Theme | Primary files |
|-------|----------------|
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

*Last updated: 2026-05-20 — aligned with Phases A–E implementation and [docs/VERIFICATION.md](./docs/VERIFICATION.md).*
