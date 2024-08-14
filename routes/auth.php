<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\ApiGuardMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth/')->name('api.')->group(function () {
    // Routes accessible to guests
    Route::middleware('guest:api')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('register', [AuthController::class, 'register'])->name('register');
    });

    // Routes accessible to authenticated users
    Route::middleware('ApiGuardMiddleware')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});

