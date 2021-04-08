<?php

use App\Http\Controllers\auth\UserLoginController;
use App\Http\Controllers\auth\UserRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserRegisterController::class, 'store'])->name('user-register.store');
Route::post('/login', [UserLoginController::class, 'store'])->name('user-login.store');

Route::group(['middleware' => 'auth:sanctum',], function () {
    Route::post('/logout', [UserLoginController::class, 'destroy'])
        ->name('user-login.destroy');
});
