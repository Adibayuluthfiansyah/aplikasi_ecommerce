<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // Categories Routes
    Route::apiResource('categories', CategoryController::class, [
        'parameters' => ['categories' => 'category_id']
    ]);

    // Customers Routes
    Route::apiResource('customers', CustomerController::class, [
        'parameters' => ['customers' => 'customer_id']
    ]);

    // Products Routes
    Route::apiResource('products', ProductController::class, [
        'parameters' => ['products' => 'product_id']
    ]);

    // Orders Routes
    Route::apiResource('orders', OrderController::class, [
        'parameters' => ['orders' => 'order_id']
    ]);

    // Order Items Routes
    Route::apiResource('order-items', OrderItemController::class, [
        'parameters' => ['order-items' => 'order_item_id']
    ]);
});
