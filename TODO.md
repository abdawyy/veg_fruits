# AL-DAWY — Master To-Do List

> **Purpose:** Actionable backlog sorted for maximum output — priority first, quick wins called out.  
> **Sources:** [PROJECT_ANALYSIS.md](./PROJECT_ANALYSIS.md) · [PROJECT_GAPS_AND_ISSUES.md](./PROJECT_GAPS_AND_ISSUES.md)  
> **Last updated:** 2026-05-20

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
- [ ] Update [README.md](./README.md) — link to `PROJECT_ANALYSIS.md` and `PROJECT_GAPS_AND_ISSUES.md`
- [x] Queue `OnOrderCreatedGenerateInvoiceAndNotify` (`ShouldQueue` + queue worker)
- [x] Server-side checkout idempotency (session flag or idempotency key)
- [x] Error at checkout if resolved cart has fewer lines than session cart ([StoreCart](./app/Support/StoreCart.php))
- [x] Enable feature tests in CI (SQLite PDO or PostgreSQL test DB)
- [ ] Invoice download from `/my` order view ([MyOrders](./app/Filament/Account/Resources/MyOrders/))
- [ ] Translated order status labels in customer account panel

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
| [ ] | 1 | Produce boxes — Filament CRUD | Medium | new `ProduceBoxResource`, models exist |
| [ ] | 2 | Produce boxes — storefront pages + cart | Hard | `StorefrontController`, routes, `StoreCart` |
| [ ] | 3 | Subscriptions — `ProcessDueSubscriptionsAction` creates orders | Hard | `ProcessDueSubscriptionsAction.php`, `CreateOrderAction.php` |
| [ ] | 4 | Subscriptions — storefront signup UI | Hard | routes, checkout integration |
| [ ] | 5 | Guest order linking on register/login | Medium | auth controllers, `Order` matching by phone/email |
| [ ] | 6 | Disable or document subscription cron until Phase D ready | **Easy** | `routes/console.php` |

---

## Phase E — Nice to have

| Done | # | Task | Ease | Primary files |
|------|---|------|------|-----------------|
| [ ] | 1 | Cart preview drawer (wire `cart_preview_*` lang keys) | Medium | new Livewire/Blade, `lang/*/aldawy.php` |
| [ ] | 2 | Category Filament resource | Easy→Med | new `CategoryResource` |
| [ ] | 3 | Coupons / discounts | Hard | schema, cart math, admin |
| [ ] | 4 | Inventory / stock management | Hard | schema, cart, admin, out-of-stock UX |
| [ ] | 5 | API write endpoints (catalog, cart, create order) | Hard | `routes/api.php`, controllers, resources |
| [ ] | 6 | Phone OTP auth | Hard | `phone_verifications`, auth flow |
| [ ] | 7 | Admin translated order status labels | **Easy** | Filament `OrderResource` |
| [ ] | 8 | Clarify `orders.packaging_fee` vs line surcharges (docs/UI) | **Easy** | admin docs, `CreateOrderAction` |
| [ ] | 9 | Excel import dry-run + row errors + audit log | Medium | `app/Imports/*`, Filament import actions |
| [ ] | 10 | CMS vs lang file guidelines (internal doc) | **Easy** | docs only |
| [ ] | 11 | Services page — clarify global vs per-product rules | **Easy** | `store/services.blade.php` |
| [ ] | 12 | Unified account UX doc (`/my` vs storefront login) | **Easy** | docs only |

---

## 1. Missing features

### By priority (highest first)

| Done | # | Feature | Notes |
|------|---|---------|-------|
| [ ] | 1 | Stock / inactive product behavior | At minimum: reject inactive at cart; later full inventory |
| [ ] | 2 | Password reset | Not implemented |
| [ ] | 3 | Customer invoice in `/my` | Today: email/SMS signed link only |
| [ ] | 4 | Guest → account order linking | `user_id` nullable; no retroactive link |
| [ ] | 5 | Status-change customer notifications | Only new-order mail today |
| [ ] | 6 | Real SMS | `LogSmsSender` only |
| [ ] | 7 | Online payments | COD only |
| [ ] | 8 | Sell by piece | Model ready; cart/checkout kg-only |
| [ ] | 9 | Email verification | Column exists; no flow |
| [ ] | 10 | Customer order cancellation | Admin-only status changes |
| [ ] | 11 | Category admin (Filament) | Seed/import only |
| [ ] | 12 | Produce boxes admin + storefront | Models exist; no CRUD/UI |
| [ ] | 13 | Subscriptions (cron + signup) | Cron counts only; no orders |
| [ ] | 14 | API commerce | `GET user`, `GET orders` only |
| [ ] | 15 | Inventory / stock fields | Not in schema |
| [ ] | 16 | Coupons / discounts | Not implemented |
| [ ] | 17 | Cart preview drawer | Lang keys exist; no UI |
| [ ] | 18 | Phone OTP auth | Table exists; email/password only |

### By ease (easiest first)

| Done | # | Feature |
|------|---|---------|
| [ ] | 1 | Cart preview drawer |
| [ ] | 2 | Category Filament resource |
| [ ] | 3 | Customer order cancellation |
| [ ] | 4 | Email verification |
| [ ] | 5 | Password reset |
| [ ] | 6 | Status-change notifications |
| [ ] | 7 | Customer invoice in `/my` |
| [ ] | 8 | Guest order linking |
| [ ] | 9 | Real SMS provider |
| [ ] | 10 | Online payment gateway |
| [ ] | 11 | Sell by piece |
| [ ] | 12 | Produce boxes admin |
| [ ] | 13 | Produce boxes storefront |
| [ ] | 14 | Subscriptions cron → orders |
| [ ] | 15 | Subscriptions storefront signup |
| [ ] | 16 | API commerce endpoints |
| [ ] | 17 | Inventory / stock |
| [ ] | 18 | Coupons / discounts |
| [ ] | 19 | Phone OTP auth |

---

## 2. Flow improvements

### 2.1 Checkout & cart

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Checkout idempotency |
| [ ] | 2 | Reject inactive products + checkout mismatch error |
| [ ] | 3 | Price change warning |
| [ ] | 4 | Post-checkout account nudge + order link on login |
| [ ] | 5 | Edit prep/packaging in cart |
| [ ] | 6 | Estimated total on product page |
| [ ] | 7 | Saved addresses |
| [ ] | 8 | Fix `register_sub` copy |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Fix `register_sub` copy |
| [ ] | 2 | Checkout idempotency (UI disable button) |
| [ ] | 3 | Post-checkout account nudge |
| [ ] | 4 | Estimated total on product page |
| [ ] | 5 | Edit prep/packaging in cart |
| [ ] | 6 | Price change warning |
| [ ] | 7 | Saved addresses |
| [ ] | 8 | Reject inactive at add |

**Files:** `app/Support/StoreCart.php` · `app/Http/Controllers/CartController.php` · `app/Http/Controllers/CheckoutController.php` · `resources/views/store/cart.blade.php` · `lang/en/aldawy.php` · `lang/ar/aldawy.php`

---

### 2.2 Order fulfillment (admin → customer)

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Queue post-order work |
| [ ] | 2 | Invoice download in `/my` |
| [ ] | 3 | Translated order status (customer + admin) |
| [ ] | 4 | Clarify `orders.packaging_fee` reporting |
| [ ] | 5 | Localize SMS template |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Localize SMS template |
| [ ] | 2 | Translated order status |
| [ ] | 3 | Clarify packaging_fee (docs) |
| [ ] | 4 | Invoice download in `/my` |
| [ ] | 5 | Queue post-order work |

**Files:** `app/Listeners/OnOrderCreatedGenerateInvoiceAndNotify.php` · `app/Actions/Invoices/GenerateInvoicePdfAction.php` · `app/Filament/Account/Resources/MyOrders/*`

---

### 2.3 Auth & account

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Password reset |
| [ ] | 2 | Redirect after register (`?redirect=`) |
| [ ] | 3 | Guest order linking |
| [ ] | 4 | Phone / OTP login |
| [ ] | 5 | Document or unify `/my` vs storefront account UX |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Redirect after register |
| [ ] | 2 | Document account UX |
| [ ] | 3 | Password reset |
| [ ] | 4 | Guest order linking |
| [ ] | 5 | Phone / OTP login |

**Files:** `app/Http/Controllers/Auth/LoginController.php` · `RegisterController.php` · `AccountPanelProvider.php`

---

### 2.4 Operations & content

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Subscriptions cron — implement or disable |
| [ ] | 2 | Excel import dry-run + errors + audit |
| [ ] | 3 | CMS vs lang guidelines |
| [ ] | 4 | Remove stale lang keys |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Remove stale lang keys |
| [ ] | 2 | Disable subscription cron until ready |
| [ ] | 3 | CMS vs lang guidelines |
| [ ] | 4 | Excel import improvements |

**Files:** `ProcessDueSubscriptionsAction.php` · `routes/console.php` · `app/Imports/*`

---

### 2.5 Observability & quality

#### Priority

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Feature tests in CI |
| [ ] | 2 | Project README |
| [ ] | 3 | More tests (idempotency, inactive cart, etc.) |

#### Ease

| Done | # | Improvement |
|------|---|-------------|
| [ ] | 1 | Project README |
| [ ] | 2 | Feature tests in CI |
| [ ] | 3 | Additional test coverage |

**Files:** `tests/Feature/CheckoutTest.php` · `tests/Feature/CartAjaxAddTest.php` · `README.md`

---

## 3. Bugs & risks

### 3.1 High impact

#### Priority

| Done | # | Issue | Fix |
|------|---|-------|-----|
| [ ] | 1 | Synchronous order listener | Queue listener |
| [ ] | 2 | Double-submit checkout | Idempotency + UI |
| [ ] | 3 | Inactive products in cart | Validate `is_active`; checkout mismatch error |
| [ ] | 4 | Feature tests skipped | CI SQLite or PG |

#### Ease

| Done | # | Issue | Fix |
|------|---|-------|-----|
| [ ] | 1 | Double-submit (UI) | Disable submit button |
| [ ] | 2 | Inactive at add | `CartController` validation |
| [ ] | 3 | Feature tests skipped | CI config |
| [ ] | 4 | Synchronous listener | `ShouldQueue` |
| [ ] | 5 | Server checkout idempotency | Session flag / key |
| [ ] | 6 | Lines dropped at resolve | Compare counts at checkout |

---

### 3.2 Medium impact

#### Priority

| Done | # | Issue |
|------|---|-------|
| [ ] | 1 | No rate limiting on cart/checkout |
| [ ] | 2 | Invoice signed URL scope (secret link; no ownership) |
| [ ] | 3 | Checkout UI `parseFloat` rounding |
| [ ] | 4 | SMS hardcoded English |
| [ ] | 5 | Misleading `register_sub` |
| [ ] | 6 | Stale `storefront_coming` copy |

#### Ease

| Done | # | Issue |
|------|---|-------|
| [ ] | 1 | `register_sub` copy |
| [ ] | 2 | `storefront_coming` copy |
| [ ] | 3 | SMS localization |
| [ ] | 4 | Cart Blade float math |
| [ ] | 5 | Rate limiting middleware |
| [ ] | 6 | Invoice scope hardening |

---

### 3.3 Low impact / polish

#### Priority

| Done | # | Issue |
|------|---|-------|
| [ ] | 1 | Raw order status in customer panel |
| [ ] | 2 | Services page vs per-product expectations |
| [ ] | 3 | All notifications sent inline (not queued) |
| [ ] | 4 | `cart_preview_*` strings unused |

#### Ease

| Done | # | Issue |
|------|---|-------|
| [ ] | 1 | Raw status labels |
| [ ] | 2 | Cart preview component |
| [ ] | 3 | Services page copy |
| [ ] | 4 | Queue all mail notifications |

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
| [ ] | Subscriptions → auto orders | Phase D | Hard |
| [ ] | Produce box checkout | Phase D | Hard |
| [ ] | Piece-based cart | Phase C | Medium |
| [ ] | Phone OTP auth | Phase E | Hard |
| [ ] | SMS (real provider) | Phase C | Easy→Med |
| [ ] | Payment (beyond COD) | Phase C | Med→Hard |
| [ ] | API (minimal today) | Phase E | Hard |
| [ ] | README (default Laravel) | Phase A / week 1 | **Easy** |

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
| | | |

---

*Check items off here and in [PROJECT_GAPS_AND_ISSUES.md](./PROJECT_GAPS_AND_ISSUES.md) when closing gaps.*
