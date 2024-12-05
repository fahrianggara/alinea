<?php

use Illuminate\Support\Facades\Route;
use GuzzleHttp\Middleware;
use App\Http\Controllers\Admin\{AuthController, BookController, BorrowingController, CategoryController, DashboardController, InvoiceController, TestingController, UserController};
use App\Http\Controllers\Alinea\{AlineaController, PickupController};
use App\Http\Controllers\Testing\CartTestController;
use App\Models\Admin;
use Faker\Guesser\Name;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->Middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [DashboardController::class, 'index'])->Middleware('auth');


// bismillah bisa

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

    Route::group(['prefix' => 'borrowings', 'as' => 'borrowings',], function () {

        Route::get('/', [BorrowingController::class, 'index'])->name('');
        Route::get('/{id}', [BorrowingController::class, 'show'])->name('.show');
        Route::post('/', [BorrowingController::class, 'store'])->name('.store');
        Route::delete('/{id}', [BorrowingController::class, 'destroy'])->name('.destroy');
        Route::put('/{id}', [BorrowingController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'invoices', 'as' => 'invoices',], function () {

        Route::get('/', [InvoiceController::class, 'index'])->name('');
        Route::delete('/deleteAll', [InvoiceController::class, 'destroyAll'])->name('.destroyAll');
        Route::get('/{no_invoice}', [InvoiceController::class, 'show'])->name('.show');
        Route::get('/download/{id}', [InvoiceController::class, 'downloadPdf'])->name('.download');
        Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('.destroy');



    });

    // In routes/web.php
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');          // List users
        Route::get('/create', [UserController::class, 'create'])->name('create');  // Show create user form
        Route::post('/', [UserController::class, 'store'])->name('store');          // Store a new user
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');    // Show edit form
        Route::put('/{id}', [UserController::class, 'update'])->name('update');     // Update user
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy'); // Delete user
    });
});


Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'alinea', 'as' => 'alinea',], function () {
        Route::get('/', [AlineaController::class, 'index'])->name('');
    });

    Route::group(['prefix' => 'pickups', 'as' => 'pickups',], function () {

        Route::get('/', [PickupController::class, 'index'])->name('');
        Route::post('/{no_invoice}', [PickupController::class, 'show'])->name('.show');
        Route::post('/borrow/{no_invoice}', [PickupController::class, 'borrow'])->name('.borrow');

    });

    Route::group(['prefix' => 'returns', 'as' => 'returns',], function () {

        Route::get('/', [PickupController::class, 'index'])->name('');
        Route::get('/{id}', [BorrowingController::class, 'show'])->name('.show');
        Route::post('/', [BorrowingController::class, 'store'])->name('.store');
        Route::delete('/{id}', [BorrowingController::class, 'destroy'])->name('.destroy');
        Route::put('/{id}', [BorrowingController::class, 'update'])->name('.update');
    });

});
