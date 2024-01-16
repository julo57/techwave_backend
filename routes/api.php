<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ResetEmailController;
use App\Http\Controllers\ResetPasswordController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products/{productId}/comments', [CommentController::class, 'store']);
Route::get('/products/{productId}/comments', [CommentController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/subscribe', [NewsletterController::class, 'subscribe']);
Route::get('/products/filter', [ProductController::class, 'filterProducts']);
Route::post('/orders', [OrderController::class, 'store']);
Route::post('/delete-account', [DeleteController::class, 'deleteAccount']);
Route::post('/change-password', [ResetPasswordController::class, 'changePassword']);
Route::post('/change-email',[ResetEmailController::class, 'changeEmail']);

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/returns', [OrderController::class, 'indexReturns']);
Route::middleware('auth:sanctum')->get('/comment', [CommentController::class, 'indexComment']);



Route::post('/returnOrder/{orderId}', [OrderController::class, 'returnOrder']);