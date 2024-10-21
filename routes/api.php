<?php

use App\Http\Controllers\Api\{BookApiController , CategoryApiController, AuthController};
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


Route::get('/categories', [CategoryApiController::class, 'index']);
Route::get('/categories/{id}', [CategoryApiController::class, 'show']);
Route::post('/categories', [CategoryApiController::class, 'store']);
Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
Route::delete(
    '/categories/{id}',
    [CategoryApiController::class, 'destroy']
);
Route::get('/books', [BookApiController::class, 'index']);
Route::get('/books/{id}', [BookApiController::class, 'show']);
Route::post('/books', [BookApiController::class, 'store']);
Route::put('/books/{id}', [BookApiController::class, 'update']);
Route::delete('/books/{id}', [BookApiController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'books'], function () {
        Route::get('/', [BookApiController::class, 'index']);
        Route::get('/{id}', [BookApiController::class, 'show']);
        Route::post('/', [BookApiController::class, 'store']);
        Route::put('/{id}', [BookApiController::class, 'update']);
        Route::delete('/{id}', [BookApiController::class, 'destroy']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryApiController::class, 'index']);
        Route::get('/{id}', [CategoryApiController::class, 'show']);
        Route::post('/', [CategoryApiController::class, 'store']);
        Route::put('/{id}', [CategoryApiController::class, 'update']);
        Route::delete('/{id}', [CategoryApiController::class, 'destroy']);
    });
});