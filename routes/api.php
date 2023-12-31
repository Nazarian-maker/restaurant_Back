<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\Order\DishOrderController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [AuthController::class, 'login']);
//!Просмотр меню
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}/dishes', [CategoryController::class, 'indexByCategory']);
Route::get('/dishes', [DishController::class, 'index']);
Route::get('/menu', [DishController::class, 'menu']);
//!Сброс пароля
Route::post('/forgot-password', [PasswordController::class, 'setEmail'])->name('password.email');
Route::get('/reset-password/{token}', function ($token) {
    return ['token' => $token];
})->name('password.reset');
Route::post('/reset-password/{token}', [PasswordController::class, 'reset'])->name('password.update');
Route::post('/reset-pin/{token}', [PasswordController::class, 'resetPin'])->name('pin_code.update');
//!Выход, отчеты и информация
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/reports', [ReportController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('dishes')->group(function () {
        Route::post('/', [DishController::class, 'store']);
        Route::get('/{id}', [DishController::class, 'show']);
        Route::put('/{id}', [DishController::class, 'update']);
        Route::delete('/{id}', [DishController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);

        Route::post('/{id}', [DishOrderController::class, 'addDish']);
        Route::put('/{id}/{dish_id}', [DishOrderController::class, 'deleteDish']);
    });
});
