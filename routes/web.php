<?php

use Illuminate\Support\Facades\Route;
use GuzzleHttp\Middleware;
use App\Http\Controllers\Admin\{AuthController, CategoryController, DashboardController};

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->Middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [DashboardController::class, 'index'])->Middleware('auth');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'category', 'as' => 'category',], function () {

        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store'])->name('.store');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('.destroy');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('.update');

    });

});
