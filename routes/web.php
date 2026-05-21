<?php

use App\Http\Controllers\AccountInvoiceDownloadController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\PhoneAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceDownloadController;
use App\Http\Controllers\ProduceBoxController;
use App\Http\Controllers\ProductEstimateController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('store.home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('store.shop');
Route::get('/fruits', [StorefrontController::class, 'fruits'])->name('store.fruits');
Route::get('/vegetables', [StorefrontController::class, 'vegetables'])->name('store.vegetables');
Route::get('/products/{product}', [StorefrontController::class, 'product'])->name('store.product');
Route::post('/products/{product}/estimate', ProductEstimateController::class)
    ->middleware('throttle:cart')
    ->name('store.product.estimate');
Route::get('/special-services', [StorefrontController::class, 'services'])->name('store.services');
Route::get('/boxes', [ProduceBoxController::class, 'index'])->name('store.boxes');
Route::get('/boxes/{produceBox}', [ProduceBoxController::class, 'show'])->name('store.boxes.show');
Route::post('/boxes/{produceBox}/cart', [ProduceBoxController::class, 'addToCart'])
    ->middleware('throttle:cart')
    ->name('store.boxes.cart');

Route::get('/cart', [CartController::class, 'index'])->name('store.cart');
Route::middleware('throttle:cart')->group(function (): void {
    Route::post('/cart/add', [CartController::class, 'add'])->name('store.cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('store.cart.update');
    Route::post('/cart/options', [CartController::class, 'updateOptions'])->name('store.cart.options');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('store.cart.remove');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('store.cart.coupon');
    Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('store.cart.coupon.remove');
});
Route::post('/cart/clear', [CartController::class, 'clear'])->name('store.cart.clear');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware('throttle:checkout')
    ->name('store.checkout.store');
Route::get('/checkout/thanks', [CheckoutController::class, 'thanks'])->name('store.checkout.thanks');

Route::middleware('guest')->group(function (): void {
    Route::get('/login/phone', [PhoneAuthController::class, 'create'])->name('phone.login');
    Route::post('/login/phone', [PhoneAuthController::class, 'sendOtp'])
        ->middleware('throttle:6,1')
        ->name('phone.login.send');
    Route::get('/login/phone/verify', [PhoneAuthController::class, 'verifyForm'])->name('phone.login.verify');
    Route::post('/login/phone/verify', [PhoneAuthController::class, 'verify'])
        ->middleware('throttle:12,1')
        ->name('phone.login.verify.submit');
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/boxes/{produceBox}/subscribe', [SubscriptionController::class, 'store'])
        ->middleware('throttle:checkout')
        ->name('store.boxes.subscribe');
    Route::get('/email/verify', [VerifyEmailController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/email/verification-notification', [VerifyEmailController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('/my/orders/{order}/invoice', AccountInvoiceDownloadController::class)
        ->name('account.orders.invoice');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/invoices/{order}/download', InvoiceDownloadController::class)
    ->middleware('signed')
    ->name('invoices.download');
