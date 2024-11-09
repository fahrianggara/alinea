<?php


use App\Http\Controllers\Api\{BookApiController, BorrowingAPIController, CartApiController, CategoryApiController, NotificationAPIController};

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Resources\ResResource;
use App\Models\Notification;
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

Route::middleware('auth:sanctum')->group(function () {
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
        Route::get('/mycart', [CartApiController::class, 'mycart']);
        Route::post('/addCart/{bookId}', [CartApiController::class, 'store']);
        Route::delete('/{cartId}', [CartApiController::class, 'destroy']);
    });

    // Borrowing Routes
    Route::group(['prefix' => 'borrowings'], function () {
        Route::get('/', [BorrowingAPIController::class, 'index']);
        Route::get('/{id}', [BorrowingAPIController::class, 'show']);
        Route::post('/', [BorrowingAPIController::class, 'store']);
        Route::put('/{id}', [BorrowingAPIController::class, 'update']);
        Route::delete('/{id}', [BorrowingAPIController::class, 'destroy']);
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationAPIController::class, 'index']);
        Route::get('/mynotif', function (Request $request) {
            // Fetch notifications for the authenticated user
            $user = $request->user();
            $notifications = Notification::where('user_id', $user->id)->get();
            return response()->json(new ResResource($notifications, true, 'User data retrieved successfully'), 200);
        });

        Route::post('/new-book/{bookId}', [NotificationAPIController::class, 'newBookNotification']);
        Route::post('/due-date/{userId}/{bookId}', [NotificationAPIController::class, 'dueDateNotification']);
        Route::post('/fined/{userId}', [NotificationAPIController::class, 'finedNotification']);
        Route::put('/{id}/mark-read', [NotificationAPIController::class, 'markAsRead']);
    });
});