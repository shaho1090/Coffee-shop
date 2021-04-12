<?php


use App\Http\Controllers\OptionsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum',], function () {

    Route::get('/orders', [OrdersController::class, 'index'])
        ->name('order.index');

    Route::get('/order/{order}', [OrdersController::class, 'show'])
        ->name('order.show');

    Route::post('/order', [OrdersController::class, 'store'])
        ->name('order.store');

});
