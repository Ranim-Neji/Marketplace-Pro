<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [CatalogController::class, 'search']);
    Route::get('/products/{product:slug}', [ProductController::class, 'show']);

    Route::middleware(['auth:sanctum', 'active'])->group(function () {
        Route::apiResource('orders', OrderController::class);
        Route::get('/user/profile', fn() => response()->json([
            'success' => true,
            'data' => auth()->user(),
        ]));
    });
});
