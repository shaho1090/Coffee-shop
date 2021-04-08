<?php

use App\Http\Controllers\auth\UserRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[UserRegisterController::class,'store'])->name('user-register.store');
