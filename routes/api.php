<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CarController;
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

Route::controller(CarController::class)
    ->group(function () {
        Route::apiResource('cars', CarController::class);

        Route::post('/users/{user}/cars/{car}', [CarController::class, 'driveCar'])
            ->name('cars.attach.user');

        Route::delete('/users/{user}/cars/', [CarController::class, 'leaveCar'])
            ->name('users.detach.car');
    });

//Route::middleware('auth:sanctum')->group(function () {
//
//});
