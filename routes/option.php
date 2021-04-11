<?php


use App\Http\Controllers\OptionsController;
use App\Models\Option;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum',], function () {
    Route::post('/option', [OptionsController::class, 'store'])
        ->name('option.store')
        ->middleware('can:create,'.Option::class);
});
