# AL-DAWY — Backlog verification (2026-05-20)

Code audit against [TODO.md](../TODO.md). Run `php artisan migrate` and `php artisan test` locally (PHP 8.3+) to confirm runtime.

## Summary

| Area | Items | Verified in code | Open / polish |
|------|-------|------------------|---------------|
| Phase A | 6 | 6 | — |
| Phase B | 9 | 9 | — |
| Phase C | 8 | 8 | — |
| Phase D | 6 | 6 | — |
| Phase E | 12 | 12 | — |
| §1 Missing features | 18 | 18 | — |
| §2 Flow improvements | all rows | all | — |
| §3 High impact | 6 | 6 | — |
| §3 Medium | 6 | 4 | Invoice URL scope, cart `parseFloat` |
| §3 Low | 4 | 4 | — |
| §4 Known gaps | 8 | 7 | README (fixed in repo after audit) |

## Phase A — Stability & trust

| # | Task | Evidence |
|---|------|----------|
| 1 | Queued order listener | `OnOrderCreatedGenerateInvoiceAndNotify` implements `ShouldQueue` |
| 2 | Checkout idempotency | `checkout_nonce`, `checkout_in_flight`, disabled submit in `cart.blade.php` |
| 3 | Inactive at cart add | `CartController::add` + `exists` rule on active products |
| 4 | Dropped lines at checkout | `StoreCart::hasDroppedLines()`, `CheckoutController` |
| 5 | Copy fixes | `register_sub` updated; no `storefront_coming` in lang |
| 6 | CI tests | `.github/workflows/tests.yml`, no `markTestSkipped` in `tests/` |

## Phase B — Customer experience

| # | Task | Evidence |
|---|------|----------|
| 1 | Invoice in `/my` | `AccountInvoiceDownloadController`, `ViewMyOrder` download action |
| 2 | Translated status | `OrderStatus::label()`, `MyOrderInfolist` |
| 3 | Register redirect | `RegisterController` `?redirect=` |
| 4 | Cart prep/packaging | `CartController::updateOptions`, `store.cart.options` |
| 5 | SMS localized | `__('aldawy.sms_invoice_ready')` in listener |
| 6 | Guest nudge | `checkout-thanks` + `showGuestAccountNudge` |
| 7 | Price change warning | `StoreCart::priceChangedLines()`, cart banner |
| 8 | Product estimate | `ProductEstimateController`, product form AJAX |
| 9 | Saved addresses | migration `default_city_id`, checkout prefill |

## Phase C — Business features

| # | Task | Evidence |
|---|------|----------|
| 1 | SMS provider | `HttpSmsSender`, `config/aldawy.php` `sms.driver` |
| 2 | Online payment | `OnlinePendingPaymentGateway`, `ALDAWY_ONLINE_PAYMENT_ENABLED` |
| 3 | Sell by piece | `StoreCart::UNIT_PIECE`, cart validation |
| 4 | Password reset | `ForgotPasswordController`, `ResetPasswordController`, routes |
| 5 | Email verification | `User` `MustVerifyEmail`, `VerifyEmailController`, middleware |
| 6 | Customer cancel | `CancelCustomerOrderAction`, `ViewMyOrder` action |
| 7 | Status notifications | `OrderObserver`, `OrderStatusChangedNotification` (queued) |
| 8 | Rate limits | `throttle:cart`, `throttle:checkout` in routes + `AppServiceProvider` |

## Phase D — Commerce

| # | Task | Evidence |
|---|------|----------|
| 1 | Produce box admin | `ProduceBoxResource` |
| 2 | Box storefront | `/boxes`, `ProduceBoxController`, `StoreCart::addBox` |
| 3 | Subscription orders | `ProcessDueSubscriptionsAction` creates orders |
| 4 | Subscription signup | `SubscriptionController`, `store.boxes.subscribe` |
| 5 | Guest linking | `LinkGuestOrdersToUserAction`, login/register |
| 6 | Cron documented | `routes/console.php` comment + daily schedule |

## Phase E — Nice to have

| # | Task | Evidence |
|---|------|----------|
| 1 | Cart preview | `⚡cart-preview-drawer.blade.php`, nav dispatches `open-cart-preview` |
| 2 | Categories admin | `CategoryResource` |
| 3 | Coupons | migration, `CouponResource`, `ApplyCouponService`, cart routes |
| 4 | Stock | `track_stock`, `stock_quantity`, cart/checkout guards |
| 5 | API v1 | `routes/api.php` catalog, quote, orders |
| 6 | Phone OTP | `PhoneAuthController`, `/login/phone` |
| 7 | Admin status labels | `OrdersTable` uses `OrderStatus::label()` |
| 8 | Packaging fee docs | `docs/PACKAGING_FEES.md`, `OrderForm` placeholder |
| 9 | Import dry-run | `SpreadsheetImportRunner`, `ListProducts` dry_run toggle |
| 10 | CMS vs lang | `docs/CMS_AND_LANG.md` |
| 11 | Services rules | `services.blade.php` rules block |
| 12 | Account UX doc | `docs/ACCOUNT_UX.md` |

## Still open (by design or polish)

| Item | Status |
|------|--------|
| **README** | Project README with doc links |
| **Invoice access** | Hardened — see [INVOICE_ACCESS.md](./INVOICE_ACCESS.md) |
| **Checkout totals JS** | Integer cents via `resources/js/money.js` + `MoneyCents` PHP |

## Local verification commands

```bash
composer install
cp .env.example .env   # if needed
php artisan key:generate
php artisan migrate
php artisan test
php artisan route:list --columns=method,uri,name | findstr /i "store boxes cart api login phone"
```

Queue worker (for PDF/mail/SMS after order):

```bash
php artisan queue:work
```
