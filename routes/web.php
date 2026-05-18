<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceDownloadController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('store.home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('store.shop');
Route::get('/fruits', [StorefrontController::class, 'fruits'])->name('store.fruits');
Route::get('/vegetables', [StorefrontController::class, 'vegetables'])->name('store.vegetables');
Route::get('/products/{product}', [StorefrontController::class, 'product'])->name('store.product');
Route::get('/special-services', [StorefrontController::class, 'services'])->name('store.services');

Route::get('/cart', [CartController::class, 'index'])->name('store.cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('store.cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('store.cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('store.cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('store.cart.clear');

Route::post('/checkout', [CheckoutController::class, 'store'])->name('store.checkout.store');
Route::get('/checkout/thanks', [CheckoutController::class, 'thanks'])->name('store.checkout.thanks');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/invoices/{order}/download', InvoiceDownloadController::class)
    ->middleware('signed')
    ->name('invoices.download');
