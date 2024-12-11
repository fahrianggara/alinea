<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\BorrowingAPIController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\NotificationAPIController;
use App\Http\Controllers\Api\UserApiController;
// use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Resources\ResResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', function (Request $request) {
        return response()->json(new ResResource($request->user(), true, 'User data retrieved successfully'), 200);
    });

    Route::delete('/logout', [AuthApiController::class, 'logout']);

    Route::group(['prefix' => 'books'], function () {
        Route::get('/', [BookApiController::class, 'index']);
        Route::post('/', [BookApiController::class, 'store']);
        Route::get('/{id}', [BookApiController::class, 'show']);
        Route::delete('/{id}', [BookApiController::class, 'destroy']);
        Route::post('/update/{id}', [BookApiController::class, 'update']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryApiController::class, 'index']);
        Route::get('/{id}', [CategoryApiController::class, 'show']);
        Route::post('/', [CategoryApiController::class, 'store']);
        Route::put('/{id}', [CategoryApiController::class, 'update']);
        Route::delete('/{id}', [CategoryApiController::class, 'destroy']);
    });

    Route::group(['prefix' => 'carts'], function () {
        Route::get('/', [CartApiController::class, 'index']);
        Route::get('/mycart', [CartApiController::class, 'mycart']);
        Route::post('/addCart/{bookId}', [CartApiController::class, 'store']);
        Route::delete('/{cartId}', [CartApiController::class, 'destroy']);
    });

    // Borrowing Routes
    Route::group(['prefix' => 'borrowings'], function () {
        Route::get('/', [BorrowingAPIController::class, 'index']);
        Route::get('/mynotif', [BorrowingAPIController::class, 'mynotif']);
        Route::post('/', [BorrowingAPIController::class, 'store']);
        Route::get('/history', [BorrowingAPIController::class, 'history']);
        Route::get('/{id}', [BorrowingAPIController::class, 'show']);
        Route::put('/{id}', [BorrowingAPIController::class, 'update']);
        Route::delete('/{id}', [BorrowingAPIController::class, 'destroy']);
        Route::delete('/mynotif/delete', [BorrowingAPIController::class, 'destroyAll']);
    });

    Route::group(['prefix' => 'invoices'], function () {
        Route::get('/myinvoice', [InvoiceApiController::class, 'myInvoice']);
        Route::get('/download/{id}', [InvoiceApiController::class, 'downloadPdf'])->name('invoices.download');
        Route::get('/{id}', [InvoiceApiController::class, 'show']);
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::post('/update', [UserApiController::class, 'updateProfile']);
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationAPIController::class, 'index']);
        Route::get('/mynotif', [NotificationAPIController::class, 'mynotif']);
        Route::delete('/delete', [NotificationAPIController::class, 'destroyAll']);
    });
});
