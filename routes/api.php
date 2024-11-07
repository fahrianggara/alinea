<?php


use App\Http\Controllers\Api\{BookApiController , CartApiController, CategoryApiController};

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
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


// Route::get('/categories', [CategoryApiController::class, 'index']);
// Route::get('/categories/{id}', [CategoryApiController::class, 'show']);
// Route::post('/categories', [CategoryApiController::class, 'store']);
// Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
// Route::delete(
//     '/categories/{id}',
//     [CategoryApiController::class, 'destroy']
// );
// Route::get('/books', [BookApiController::class, 'index']);
// Route::get('/books/{id}', [BookApiController::class, 'show']);
// Route::post('/books', [BookApiController::class, 'store']);
// Route::put('/books/{id}', [BookApiController::class, 'update']);
// Route::delete('/books/{id}', [BookApiController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function ()
{
    Route::get('/me', function (Request $request) {
        return response()->json(new ResResource($request->user(), true, 'User data retrieved successfully'), 200);
    });

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

    Route::group(['prefix' => 'carts'], function () {
        Route::get('/', [CartApiController::class, 'index']);
        Route::get('/{id}', [CartApiController::class, 'show']);
        Route::post('/', [CartApiController::class, 'store']);
        Route::put('/{id}', [CartApiController::class, 'update']);
        Route::delete('/{id}', [CartApiController::class, 'destroy']);
    });
});

