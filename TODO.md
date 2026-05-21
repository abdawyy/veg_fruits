# AL-DAWY — Master To-Do List

> **Purpose:** Actionable backlog sorted for maximum output — priority first, quick wins called out.  
> **Sources:** [PROJECT_ANALYSIS.md](./PROJECT_ANALYSIS.md) · [PROJECT_GAPS_AND_ISSUES.md](./PROJECT_GAPS_AND_ISSUES.md)  
> **Last updated:** 2026-05-20  
> **Verification:** [docs/VERIFICATION.md](./docs/VERIFICATION.md) — code audit of Phases A–E and §1–§4

**How to use this file**

- Work **Phase A** before scaling traffic.
- Within each section: **Priority** = do first · **Ease** = fastest wins.
- Check boxes as you ship; link PRs in parentheses if helpful.
- Update this file when items close or new gaps appear.

---

## This week — highest impact, lowest effort

Do these first for the best ROI:

- [x] Fix `register_sub` — remove “coming soon” ([lang/en/aldawy.php](./lang/en/aldawy.php), [lang/ar/aldawy.php](./lang/ar/aldawy.php))
- [x] Remove or update stale `storefront_coming` lang keys
- [x] Disable checkout submit button after first click ([CheckoutController](./app/Http/Controllers/CheckoutController.php), checkout view)
- [x] Reject inactive products on cart add (`is_active` in [CartController](./app/Http/Controllers/CartController.php))
- [x] Update [README.md](./README.md) — link to `PROJECT_ANALYSIS.md` and `PROJECT_GAPS_AND_ISSUES.md`
- [x] Queue `OnOrderCreatedGenerateInvoiceAndNotify` (`ShouldQueue` + queue worker)
- [x] Server-side checkout idempotency (session flag or idempotency key)
- [x] Error at checkout if resolved cart has fewer lines than session cart ([StoreCart](./app/Support/StoreCart.php))
- [x] Enable feature tests in CI (SQLite PDO or PostgreSQL test DB)
- [x] Invoice download from `/my` order view ([MyOrders](./app/Filament/Account/Resources/MyOrders/))
- [x] Translated order status labels in customer account panel

---

## Phase A — Stability & trust (do first)

| Done | # | Task | Ease | Primary files |
|------|---|------|------|-----------------|
| [x] | 1 | Queue `OrderCreated` listener (PDF + mail + SMS) | Medium | `app/Listeners/OnOrderCreatedGenerateInvoiceAndNotify.php` |
| [x] | 2 | Prevent duplicate checkout (UI + server idempotency) | Easy→Med | `CheckoutController.php`, checkout Blade |
| [x] | 3 | Reject inactive products at add-to-cart | Easy | `CartController.php` |
| [x] | 4 | Checkout error if session lines ≠ resolved cart | Easy→Med | `StoreCart.php`, `CheckoutController.php` |
| [x] | 5 | Fix misleading copy (`register_sub`, `storefront_coming`) | **Easy** | `lang/en/aldawy.php`, `lang/ar/aldawy.php` |
| [x] | 6 | Feature tests run in CI (no skip) | Easy→Med | `tests/Feature/CheckoutTest.php`, `CartAjaxAddTest.php`, CI config |

---

## Phase B — Customer experience

| Done | # | Task | Ease | Primary files |
|------|---|------|------|-----------------|
| [x] | 1 | Invoice download from `/my` orders | **Easy** | `app/Filament/Account/Resources/MyOrders/*` |
| [x] | 2 | Translated order status in account panel | **Easy** | `MyOrderInfolist`, account resources |
| [x] | 3 | Redirect to cart after register (`?redirect=`) | **Easy** | `RegisterController.php` |
| [x] | 4 | Edit prep/packaging on cart lines | Medium | `StoreCart.php`, `CartController.php`, cart Blade |
| [x] | 5 | Localize SMS template (EN/AR) | **Easy** | `OnOrderCreatedGenerateInvoiceAndNotify.php`, lang files |
| [x] | 6 | Post-checkout guest nudge (register/login to track order) | Medium | thanks page, auth controllers |
| [x] | 7 | Price change warning (stale cart vs live DB) | Medium | `StoreCart.php`, cart/checkout views |
| [x] | 8 | Estimated line total on product page (optional AJAX) | Medium | product Blade, pricing services |
| [x] | 9 | Saved addresses / default city for logged-in users | Hard | new migration + checkout prefill |

---

## Phase C — Business features

| Done | # | Task | Ease | Primary files |
|------|---|------|------|-----------------|
| [x] | 1 | Real SMS provider (`SmsSenderInterface`) | Easy→Med | `app/Sms/*`, `AppServiceProvider.php` |
| [x] | 2 | Additional payment gateway (non-COD) | Med→Hard | `app/Payments/*`, `AppServiceProvider.php`, checkout |
| [x] | 3 | Sell by piece in cart + checkout | Medium | `StoreCart.php`, `CheckoutController.php`, cart views |
| [x] | 4 | Password reset flow | **Easy** | auth routes, controllers, views |
| [x] | 5 | Email verification (optional enforcement) | Easy→Med | `User` model, middleware, notifications |
| [x] | 6 | Customer order cancellation | Easy→Med | `/my` orders, `OrderStatus` rules |
| [x] | 7 | Status-change notifications (shipped, delivered, etc.) | Medium | order observer/listener, notifications |
| [x] | 8 | Rate limiting on cart add + checkout | Easy→Med | `routes/web.php`, `bootstrap/app.php` |

---

## Phase D — Phase 2 commerce

| Done | # | Task | Ease | Primary files |
|------|---|------|------|-----------------|
| [x] | 1 | Produce boxes — Filament CRUD | Medium | new `ProduceBoxResource`, models exist |
| [x] | 2 | Produce boxes — storefront pages + cart | Hard | `StorefrontController`, routes, `StoreCart` |
| [x] | 3 | Subscriptions — `ProcessDueSubscriptionsAction` creates orders | Hard | `ProcessDueSubscriptionsAction.php`, `CreateOrderAction.php` |
| [x] | 4 | Subscriptions — storefront signup UI | Hard | routes, checkout integration |
| [x] | 5 | Guest order linking on register/login | Medium | auth controllers, `Order` matching by phone/email |
| [x] | 6 | Disable or document subscription cron until Phase D ready | **Easy** | `routes/console.php` |

---

## Phase E — Nice to have

| Done | # | Task | Ease | Primary files |
|------|---|------|------|-----------------|
| [x] | 1 | Cart preview drawer (wire `cart_preview_*` lang keys) | Medium | new Livewire/Blade, `lang/*/aldawy.php` |
| [x] | 2 | Category Filament resource | Easy→Med | new `CategoryResource` |
| [x] | 3 | Coupons / discounts | Hard | schema, cart math, admin |
| [x] | 4 | Inventory / stock management | Hard | schema, cart, admin, out-of-stock UX |
| [x] | 5 | API write endpoints (catalog, cart, create order) | Hard | `routes/api.php`, controllers, resources |
| [x] | 6 | Phone OTP auth | Hard | `phone_verifications`, auth flow |
| [x] | 7 | Admin translated order status labels | **Easy** | Filament `OrderResource` |
| [x] | 8 | Clarify `orders.packaging_fee` vs line surcharges (docs/UI) | **Easy** | admin docs, `CreateOrderAction` |
| [x] | 9 | Excel import dry-run + row errors + audit log | Medium | `app/Imports/*`, Filament import actions |
| [x] | 10 | CMS vs lang file guidelines (internal doc) | **Easy** | docs only |
| [x] | 11 | Services page — clarify global vs per-product rules | **Easy** | `store/services.blade.php` |
| [x] | 12 | Unified account UX doc (`/my` vs storefront login) | **Easy** | docs only |

---

## 1. Missing features

> **Status:** Shipped in Phases A–E (2026-05-20). Section kept as a checklist mirror of the original gap list.

### By priority (highest first)

| Done | # | Feature | Notes |
|------|---|---------|-------|
| [x] | 1 | Stock / inactive product behavior | Inactive rejected at cart; `track_stock` + quantity checks (Phase E) |
| [x] | 2 | Password reset | Forgot/reset routes + views (Phase C) |
| [x] | 3 | Customer invoice in `/my` | `AccountInvoiceDownloadController` + Filament action (Phase B) |
| [x] | 4 | Guest → account order linking | `LinkGuestOrdersToUserAction` on login/register (Phase B/D) |
| [x] | 5 | Status-change customer notifications | `OrderObserver` + queued mail (Phase C) |
| [x] | 6 | Real SMS | `HttpSmsSender` + `ALDAWY_SMS_DRIVER` (Phase C) |
| [x] | 7 | Online payments | `OnlinePendingPaymentGateway` when enabled (Phase C) |
| [x] | 8 | Sell by piece | `StoreCart` kg/piece + UI (Phase C) |
| [x] | 9 | Email verification | `MustVerifyEmail` + routes (Phase C) |
| [x] | 10 | Customer order cancellation | `CancelCustomerOrderAction` on `/my` (Phase C) |
| [x] | 11 | Category admin (Filament) | `CategoryResource` (Phase E) |
| [x] | 12 | Produce boxes admin + storefront | CRUD + `/boxes` + cart (Phase D) |
| [x] | 13 | Subscriptions (cron + signup) | `ProcessDueSubscriptionsAction` + signup UI (Phase D) |
| [x] | 14 | API commerce | `GET/POST /api/v1/catalog`, quote, orders (Phase E) |
| [x] | 15 | Inventory / stock fields | Migration + admin + storefront UX (Phase E) |
| [x] | 16 | Coupons / discounts | Schema + cart/checkout + admin (Phase E) |
| [x] | 17 | Cart preview drawer | Livewire `cart-preview-drawer` (Phase E) |
| [x] | 18 | Phone OTP auth | `/login/phone` flow (Phase E) |

### By ease (easiest first)

| Done | # | Feature |
|------|---|---------|
| [x] | 1 | Cart preview drawer |
| [x] | 2 | Category Filament resource |
| [x] | 3 | Customer order cancellation |
| [x] | 4 | Email verification |
| [x] | 5 | Password reset |
| [x] | 6 | Status-change notifications |
| [x] | 7 | Customer invoice in `/my` |
| [x] | 8 | Guest order linking |
| [x] | 9 | Real SMS provider |
| [x] | 10 | Online payment gateway |
| [x] | 11 | Sell by piece |
| [x] | 12 | Produce boxes admin |
| [x] | 13 | Produce boxes storefront |
| [x] | 14 | Subscriptions cron → orders |
| [x] | 15 | Subscriptions storefront signup |
| [x] | 16 | API commerce endpoints |
| [x] | 17 | Inventory / stock |
| [x] | 18 | Coupons / discounts |
| [x] | 19 | Phone OTP auth |

---

## 2. Flow improvements

### 2.1 Checkout & cart

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Checkout idempotency |
| [x] | 2 | Reject inactive products + checkout mismatch error |
| [x] | 3 | Price change warning |
| [x] | 4 | Post-checkout account nudge + order link on login |
| [x] | 5 | Edit prep/packaging in cart |
| [x] | 6 | Estimated total on product page |
| [x] | 7 | Saved addresses |
| [x] | 8 | Fix `register_sub` copy |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Fix `register_sub` copy |
| [x] | 2 | Checkout idempotency (UI disable button) |
| [x] | 3 | Post-checkout account nudge |
| [x] | 4 | Estimated total on product page |
| [x] | 5 | Edit prep/packaging in cart |
| [x] | 6 | Price change warning |
| [x] | 7 | Saved addresses |
| [x] | 8 | Reject inactive at add |

**Files:** `app/Support/StoreCart.php` · `app/Http/Controllers/CartController.php` · `app/Http/Controllers/CheckoutController.php` · `resources/views/store/cart.blade.php` · `lang/en/aldawy.php` · `lang/ar/aldawy.php`

---

### 2.2 Order fulfillment (admin → customer)

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Queue post-order work |
| [x] | 2 | Invoice download in `/my` |
| [x] | 3 | Translated order status (customer + admin) |
| [x] | 4 | Clarify `orders.packaging_fee` reporting |
| [x] | 5 | Localize SMS template |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Localize SMS template |
| [x] | 2 | Translated order status |
| [x] | 3 | Clarify packaging_fee (docs) |
| [x] | 4 | Invoice download in `/my` |
| [x] | 5 | Queue post-order work |

**Files:** `app/Listeners/OnOrderCreatedGenerateInvoiceAndNotify.php` · `app/Actions/Invoices/GenerateInvoicePdfAction.php` · `app/Filament/Account/Resources/MyOrders/*`

---

### 2.3 Auth & account

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Password reset |
| [x] | 2 | Redirect after register (`?redirect=`) |
| [x] | 3 | Guest order linking |
| [x] | 4 | Phone / OTP login |
| [x] | 5 | Document or unify `/my` vs storefront account UX |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Redirect after register |
| [x] | 2 | Document account UX |
| [x] | 3 | Password reset |
| [x] | 4 | Guest order linking |
| [x] | 5 | Phone / OTP login |

**Files:** `app/Http/Controllers/Auth/LoginController.php` · `RegisterController.php` · `AccountPanelProvider.php`

---

### 2.4 Operations & content

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Subscriptions cron — implement or disable |
| [x] | 2 | Excel import dry-run + errors + audit |
| [x] | 3 | CMS vs lang guidelines |
| [x] | 4 | Remove stale lang keys |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Remove stale lang keys |
| [x] | 2 | Disable subscription cron until ready |
| [x] | 3 | CMS vs lang guidelines |
| [x] | 4 | Excel import improvements |

**Files:** `ProcessDueSubscriptionsAction.php` · `routes/console.php` · `app/Imports/*`

---

### 2.5 Observability & quality

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Feature tests in CI |
| [x] | 2 | Project README |
| [x] | 3 | More tests (idempotency, inactive cart, etc.) |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [x] | 1 | Project README |
| [x] | 2 | Feature tests in CI |
| [x] | 3 | Additional test coverage |

**Files:** `tests/Feature/CheckoutTest.php` · `tests/Feature/CartAjaxAddTest.php` · `README.md`

---

## 3. Bugs & risks

### 3.1 High impact

#### Priority

| Done | # | Issue | Fix |
|------|---|-------|-----|
| [x] | 1 | Synchronous order listener | Queue listener |
| [x] | 2 | Double-submit checkout | Idempotency + UI |
| [x] | 3 | Inactive products in cart | Validate `is_active`; checkout mismatch error |
| [x] | 4 | Feature tests skipped | CI SQLite or PG |

#### Ease

| Done | # | Issue | Fix |
|------|---|-------|-----|
| [x] | 1 | Double-submit (UI) | Disable submit button |
| [x] | 2 | Inactive at add | `CartController` validation |
| [x] | 3 | Feature tests skipped | CI config |
| [x] | 4 | Synchronous listener | `ShouldQueue` |
| [x] | 5 | Server checkout idempotency | Session flag / key |
| [x] | 6 | Lines dropped at resolve | Compare counts at checkout |

---

### 3.2 Medium impact

#### Priority

| Done | # | Issue |
|------|---|-------|
| [x] | 1 | No rate limiting on cart/checkout |
| [x] | 2 | Invoice signed URL scope (secret link; no ownership) |
| [x] | 3 | Checkout UI `parseFloat` rounding |
| [x] | 4 | SMS hardcoded English |
| [x] | 5 | Misleading `register_sub` |
| [x] | 6 | Stale `storefront_coming` copy |

#### Ease

| Done | # | Issue |
|------|---|-------|
| [x] | 1 | `register_sub` copy |
| [x] | 2 | `storefront_coming` copy |
| [x] | 3 | SMS localization |
| [x] | 4 | Cart Blade float math |
| [x] | 5 | Rate limiting middleware |
| [x] | 6 | Invoice scope hardening |

---

### 3.3 Low impact / polish

#### Priority

| Done | # | Issue |
|------|---|-------|
| [x] | 1 | Raw order status in customer panel |
| [x] | 2 | Services page vs per-product expectations |
| [x] | 3 | All notifications sent inline (not queued) |
| [x] | 4 | `cart_preview_*` strings unused |

#### Ease

| Done | # | Issue |
|------|---|-------|
| [x] | 1 | Raw status labels |
| [x] | 2 | Cart preview component |
| [x] | 3 | Services page copy |
| [x] | 4 | Queue all mail notifications |

---

### 3.4 Security (awareness — not bugs)

| Item | Status |
|------|--------|
| Prep/packaging IDs filtered per product in `StoreCart::resolved()` | OK |
| Admin panel gated by `is_admin` | OK |
| Invoice signed URLs — treat as secrets (30-day expiry) | OK — be careful sharing links |

---

## 4. Known gaps (from PROJECT_ANALYSIS)

| Done | Gap | Priority | Ease |
|------|-----|----------|------|
| [x] | Subscriptions → auto orders | Phase D | Hard |
| [x] | Produce box checkout | Phase D | Hard |
| [x] | Piece-based cart | Phase C | Medium |
| [x] | Phone OTP auth | Phase E | Hard |
| [x] | SMS (real provider) | Phase C | Easy→Med |
| [x] | Payment (beyond COD) | Phase C | Med→Hard |
| [x] | API (minimal today) | Phase E | Hard |
| [x] | README (default Laravel) | Phase A / week 1 | **Easy** |

---

## 5. File index by theme

| Theme | Start here |
|-------|------------|
| Cart / checkout | `StoreCart.php`, `CartController.php`, `CheckoutController.php`, `CreateOrderAction.php` |
| Post-order | `OrderCreated.php`, `OnOrderCreatedGenerateInvoiceAndNotify.php`, `GenerateInvoicePdfAction.php` |
| Subscriptions | `ProcessDueSubscriptionsAction.php`, `ProcessSubscriptionOrdersCommand.php`, `routes/console.php` |
| Auth | `LoginController.php`, `RegisterController.php`, `PhoneVerification` model |
| Payments / SMS | `AppServiceProvider.php`, `CashOnDeliveryGateway.php`, `LogSmsSender.php` |
| Customer account | `app/Filament/Account/Resources/MyOrders/*` |
| Copy / i18n | `lang/en/aldawy.php`, `lang/ar/aldawy.php` |
| Tests | `tests/Feature/CheckoutTest.php`, `tests/Feature/CartAjaxAddTest.php` |

---

## Progress log

| Date | Completed | Notes |
|------|-----------|-------|
| 2026-05-20 | Phases A–E | Checkout, account, commerce, Phase E nice-to-haves |
| 2026-05-20 | §1 Missing features | All 18 items closed — synced with shipped work |
| 2026-05-20 | Verification pass | [docs/VERIFICATION.md](./docs/VERIFICATION.md); README updated; 2 polish items remain in §3.2 |
| 2026-05-20 | Final polish | Invoice auth, thanks invoice link, cents-based checkout JS, §3.2 closed |

---

*Check items off here and in [PROJECT_GAPS_AND_ISSUES.md](./PROJECT_GAPS_AND_ISSUES.md) when closing gaps.*
