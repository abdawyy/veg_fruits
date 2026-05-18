<?php

use App\Http\Controllers\InvoiceDownloadController;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
})->middleware('auth:sanctum');

Route::get('/orders', function (Request $request) {
    $orders = Order::query()
        ->where('user_id', $request->user()->id)
        ->with('items')
        ->latest()
        ->paginate(20);

    return OrderResource::collection($orders);
})->middleware('auth:sanctum');
