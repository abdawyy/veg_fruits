<?php

use App\Http\Controllers\Api\V1\CartQuoteController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\StoreOrderController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return new \App\Http\Resources\UserResource($request->user());
})->middleware('auth:sanctum');

Route::get('/orders', function (Request $request) {
    $orders = Order::query()
        ->where('user_id', $request->user()->id)
        ->with('items')
        ->latest()
        ->paginate(20);

    return OrderResource::collection($orders);
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function (): void {
    Route::get('/catalog/categories', [CatalogController::class, 'categories']);
    Route::get('/catalog/products', [CatalogController::class, 'products']);
    Route::get('/catalog/products/{product}', [CatalogController::class, 'show']);

    Route::post('/cart/quote', CartQuoteController::class)->middleware('throttle:cart');

    Route::post('/orders', [StoreOrderController::class, 'store'])
        ->middleware(['throttle:checkout']);
});
