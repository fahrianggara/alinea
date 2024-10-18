<?php

use Illuminate\Support\Facades\Route;
use GuzzleHttp\Middleware;
use App\Http\Controllers\Admin\{AuthController, BookController, CategoryController, DashboardController};
use Faker\Guesser\Name;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->Middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [DashboardController::class, 'index'])->Middleware('auth');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'categories', 'as' => 'categories',], function () {

        Route::get('/', [CategoryController::class, 'index'])->name('');
        Route::post('/', [CategoryController::class, 'store'])->name('.store');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('.destroy');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('.update');

    });

    Route::group(['prefix' => 'books', 'as' => 'books',], function () {

        Route::get('/', [BookController::class, 'index'])->name('');
        Route::get('/{id}', [BookController::class, 'show'])->name('.show');
        Route::post('/', [BookController::class, 'store'])->name('.store');
        Route::delete('/{id}', [BookController::class, 'destroy'])->name('.destroy');
        Route::put('/{id}', [BookController::class, 'update'])->name('.update');

    });

});
