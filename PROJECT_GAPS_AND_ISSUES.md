# AL-DAWY — Gaps, Flow Improvements & Issues

> **Purpose:** Actionable audit of missing features, UX/flow enhancements, and bugs/risks.  
> **Companion doc:** See [PROJECT_ANALYSIS.md](./PROJECT_ANALYSIS.md) for architecture and codebase map.  
> **Last reviewed:** 2026-05-20 (codebase audit)

---

## Table of contents

1. [Missing features](#1-missing-features)
2. [Flows to enhance](#2-flows-to-enhance)
3. [Bugs and risks](#3-bugs-and-risks)
4. [Suggested priority roadmap](#4-suggested-priority-roadmap)

---

## 1. Missing features

Features that exist in the database, admin, or interfaces but are **not fully exposed** on the storefront or in production integrations.

| Feature | What exists today | What's missing |
|---------|-------------------|----------------|
| **Subscriptions / produce boxes** | `subscriptions`, `produce_boxes`, `produce_box_items` tables; cron `aldawy:process-subscriptions` | Cron only **counts** due subscriptions — does **not** create orders. No storefront signup. No Filament CRUD for produce boxes. |
| **Sell by piece** | `sell_by_piece`, `price_per_piece` on products; shown on product cards | Cart and checkout always use **kg** only (`unit: 'kg'` in `CheckoutController`). |
| **Phone OTP auth** | `phone_verifications` table | No OTP login/register flow; auth is email + password only. |
| **Real SMS** | `SmsSenderInterface` contract | Only `LogSmsSender` (writes to Laravel log). |
| **Online payments** | `PaymentGatewayInterface` contract | Only **COD** via `CashOnDeliveryGateway`. |
| **API commerce** | Sanctum + `GET /api/user`, `GET /api/orders` | No endpoints to create orders, manage cart, or list catalog. |
| **Inventory / stock** | — | No stock fields, reservations, or out-of-stock behavior. |
| **Categories admin** | `categories` table + seeders | No Filament **Category** resource; categories are seed/data-import only. |
| **Produce boxes admin** | Eloquent models | No Filament resource or storefront pages. |
| **Password reset** | — | No forgot-password routes or controllers. |
| **Email verification** | `email_verified_at` on `users` | No verification flow or enforcement. |
| **Coupons / discounts** | — | Not implemented. |
| **Customer order cancellation** | `OrderStatus::Cancelled` enum | Only admins change status in Filament; customers cannot cancel. |
| **Status-change notifications** | Mail on **new** order | No email/SMS when admin moves order to shipped/delivered/etc. |
| **Cart preview drawer** | Lang keys `cart_preview_*` in `lang/*/aldawy.php` | **No Blade/Livewire component** uses those strings (dead copy). |
| **Guest → account order linking** | Guest checkout (`user_id` nullable) | Registering later does **not** attach past guest orders to the new account. |

---

## 2. Flows to enhance

### 2.1 Checkout & cart

| Improvement | Current behavior | Recommended direction |
|-------------|------------------|----------------------|
| **Estimated total on product page** | Cart page shows subtotal + shipping; product page does not | Show estimated line total when prep/packaging selected (optional AJAX). |
| **Edit line options in cart** | User can change **kg** only | Allow changing preparation services and packaging on cart rows, or prompt to re-add. |
| **Price change warning** | Checkout reprices from live DB (`StoreCart::resolved()`) | Warn if cart was built before a price update, or snapshot prices at add-to-cart. |
| **Checkout idempotency** | Plain POST to `/checkout` | Disable submit button after click, or use idempotency key / session lock to prevent duplicate orders. |
| **Post-checkout account** | Thanks page uses session `checkout_order_id` | Nudge guests to register/login to track order; link order to user if they log in. |
| **Saved addresses** | Prefill name/phone/email for logged-in users | Saved address book, default city, optional address line templates. |
| **Register copy** | `register_sub` says *"faster checkout (coming soon)"* | Update copy — registration and checkout already work. |

**Relevant files:**

- `app/Support/StoreCart.php`
- `app/Http/Controllers/CartController.php`
- `app/Http/Controllers/CheckoutController.php`
- `resources/views/store/cart.blade.php`
- `lang/en/aldawy.php`, `lang/ar/aldawy.php`

### 2.2 Order fulfillment (admin → customer)

| Improvement | Current behavior | Recommended direction |
|-------------|------------------|----------------------|
| **Async post-order work** | `OnOrderCreatedGenerateInvoiceAndNotify` runs **synchronously** on HTTP request | Queue listener: PDF + mail + SMS. |
| **Customer invoice access** | Signed URL in email/SMS only | Add download action in `/my` order view (signed or auth-scoped). |
| **Admin status labels** | Filament uses `OrderStatus::name` (e.g. `Pending`) | Translated labels for EN/AR staff UI. |
| **Order-level packaging fee** | Checkout always passes `packagingFee: '0'` | Clarify reporting: per-line packaging is in surcharges; document or populate `orders.packaging_fee` if needed. |

**Relevant files:**

- `app/Listeners/OnOrderCreatedGenerateInvoiceAndNotify.php`
- `app/Actions/Invoices/GenerateInvoicePdfAction.php`
- `app/Filament/Account/Resources/MyOrders/`

### 2.3 Auth & account

| Improvement | Current behavior | Recommended direction |
|-------------|------------------|----------------------|
| **Phone as login** | `phone_number` optional on user | Optional phone login or OTP (ties to `phone_verifications`). |
| **Redirect after register** | Always `store.home` | Support `?redirect=` like login (e.g. return to cart). |
| **Account vs storefront** | `/my` uses Filament with store `/login` | Document UX; optional unified “My account” on storefront. |

**Relevant files:**

- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Providers/Filament/AccountPanelProvider.php`

### 2.4 Operations & content

| Improvement | Current behavior | Recommended direction |
|-------------|------------------|----------------------|
| **Subscriptions cron** | `ProcessDueSubscriptionsAction` returns count only | Implement order creation or disable schedule until Phase 2. |
| **Excel import** | Maatwebsite import on several resources | Dry-run preview, row-level error report, import audit log. |
| **Two sources of UI text** | `lang/*/aldawy.php` + `content_strings` CMS | Guidelines: which keys live in CMS vs lang files. |
| **Stale lang keys** | `storefront_coming` still mentions cart “next iteration” | Remove or update unused strings. |

### 2.5 Observability & quality

| Improvement | Current behavior | Recommended direction |
|-------------|------------------|----------------------|
| **Feature tests** | `CheckoutTest`, `CartAjaxAddTest` skip without SQLite PDO | Use PostgreSQL in CI or enable `pdo_sqlite` in PHP build. |
| **Project README** | Default Laravel README | Link to `PROJECT_ANALYSIS.md` and this file. |

---

## 3. Bugs and risks

Severity: **High** → fix before scale; **Medium** → fix soon; **Low** → polish.

### 3.1 High impact

| Issue | Details | Where to look |
|-------|---------|-------------|
| **Synchronous order listener** | Checkout HTTP request waits for DomPDF generation + email + SMS. Slow UX; failures can affect the checkout response. | `app/Listeners/OnOrderCreatedGenerateInvoiceAndNotify.php` — no `ShouldQueue` in codebase |
| **Double-submit checkout** | Two POSTs to `/checkout` can create **two orders**, two emails, two PDFs. | `CheckoutController@store` — no idempotency |
| **Inactive products in cart** | `CartController@add` validates `exists:products,id` but **not** `is_active`. Inactive lines stay in session; `StoreCart::resolved()` **silently drops** them → order may have **fewer lines** than the cart table showed. | `CartController.php`, `StoreCart::resolved()` |
| **Feature tests skipped** | Integration tests for cart AJAX and checkout skip when SQLite PDO is unavailable. Regressions may go unnoticed. | `tests/Feature/CheckoutTest.php`, `tests/Feature/CartAjaxAddTest.php` |

### 3.2 Medium impact

| Issue | Details | Where to look |
|-------|---------|-------------|
| **SMS not localized** | Hardcoded English in listener: `Your AL-DAWY invoice is ready...` | `OnOrderCreatedGenerateInvoiceAndNotify.php` |
| **Invoice download scope** | Signed URL only — no ownership check. Anyone with link can download within expiry (30 days). | `InvoiceDownloadController`, signed route in listener |
| **Checkout UI float math** | City total uses `parseFloat` in inline JS — display rounding only; server is authoritative but UI can look wrong. | `resources/views/store/cart.blade.php` (inline script) |
| **No rate limiting on cart/checkout** | Login has auth throttle; cart add and checkout do not. | `routes/web.php`, `bootstrap/app.php` |
| **Misleading register copy** | Says checkout benefits “coming soon” while feature is live. | `lang/en/aldawy.php` → `register_sub` |
| **Unused stale copy** | `storefront_coming` implies cart/checkout not built yet. | `lang/*/aldawy.php` |

### 3.3 Low impact / polish

| Issue | Details | Where to look |
|-------|---------|-------------|
| **Raw order status in customer panel** | Shows enum value (e.g. `pending`) not friendly translated label. | `MyOrderInfolist`, Filament account resources |
| **Services page expectations** | Lists all global prep/packaging rules, not per-product availability. | `StorefrontController@services`, `store/services.blade.php` |
| **No queued notifications anywhere** | Entire app sends mail inline. | `app/Notifications/*` |
| **Cart preview strings unused** | `cart_preview_*` keys with no UI. | `lang/*/aldawy.php` |

### 3.4 Security notes (not bugs, but be aware)

- **Cart add:** Preparation/packaging IDs are filtered at resolve time to **enabled options for that product** — arbitrary IDs from other products do not inflate price (verified in `StoreCart::resolved()`).
- **Admin panel:** Gated by `users.is_admin` and `FilamentAuthenticate`.
- **Invoice URLs:** Time-limited signed routes — appropriate for email/SMS; treat links as secrets.

---

## 4. Suggested priority roadmap

### Phase A — Stability & trust (do first)

1. Queue `OrderCreated` listener (PDF + notifications).
2. Prevent duplicate checkout (UI disable + server-side idempotency or session flag).
3. Reject inactive products at add-to-cart; at checkout, error if session lines were dropped vs. resolved cart.
4. Fix misleading copy (`register_sub`, remove/update `storefront_coming`).
5. Enable feature tests in CI (SQLite or PostgreSQL test DB).

### Phase B — Customer experience

1. Invoice download from `/my` orders.
2. Translated order status in account panel.
3. Redirect to cart after register when appropriate.
4. Optional: edit prep/packaging on cart lines.
5. Localize SMS template.

### Phase C — Business features

1. Real SMS provider binding.
2. Additional payment gateway (if required).
3. Sell-by-piece in cart/checkout (if product mix needs it).
4. Password reset + optional email verification.

### Phase D — Phase 2 commerce

1. Produce boxes: Filament CRUD + storefront.
2. Subscriptions: implement `ProcessDueSubscriptionsAction` → `CreateOrderAction`.
3. Guest order linking on register/login.

### Phase E — Nice to have

- Cart preview drawer (wire up existing lang keys).
- Coupons, stock management, API write endpoints.
- Status-change customer notifications.
- Category Filament resource.

---

## Quick reference: files to touch by theme

| Theme | Primary files |
|-------|----------------|
| Cart / checkout | `StoreCart.php`, `CartController.php`, `CheckoutController.php`, `CreateOrderAction.php` |
| Post-order | `OrderCreated.php`, `OnOrderCreatedGenerateInvoiceAndNotify.php`, `GenerateInvoicePdfAction.php` |
| Subscriptions | `ProcessDueSubscriptionsAction.php`, `ProcessSubscriptionOrdersCommand.php`, `routes/console.php` |
| Auth | `LoginController.php`, `RegisterController.php`, `PhoneVerification` model |
| Payments / SMS | `AppServiceProvider.php`, `CashOnDeliveryGateway.php`, `LogSmsSender.php` |
| Customer account | `app/Filament/Account/Resources/MyOrders/*` |
| Copy / i18n | `lang/en/aldawy.php`, `lang/ar/aldawy.php` |
| Tests | `tests/Feature/CheckoutTest.php`, `tests/Feature/CartAjaxAddTest.php` |

---

*Update this document when closing items or discovering new gaps during development.*
