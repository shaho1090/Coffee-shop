<?php


use App\Http\Controllers\OptionsController;
use App\Http\Controllers\ProductsController;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum',], function () {

    Route::get('/products', [ProductsController::class, 'index'])
        ->name('product.index');

    Route::get('/product/{product}', [ProductsController::class, 'show'])
        ->name('product.show');

    Route::post('/product', [ProductsController::class, 'store'])
        ->name('product.store')
        ->middleware('can:create,'.Product::class);

});
