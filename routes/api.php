<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

/*
 * Auth logic
 */
Route::controller(AuthController::class)
    ->group(function () {
        Route::middleware(['guest:sanctum'])->group(function () {
            Route::post('/login', 'login')
                ->name('login');
        });

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/user', 'user')
                ->name('user');

            Route::post('/logout', 'logout')
                ->name('logout');
        });
    });

Route::middleware('auth:sanctum')->group(function () {

});
