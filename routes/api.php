<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\BrandController;
// use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\EvaluatesController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\DeliveryAddressController;
use App\Http\Controllers\Api\FavoriteController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth
Route::prefix('/')->group(function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register.create');

    Route::post('update-info', [UserController::class, 'updateInfo'])->name('updateInfo');

    Route::post('check-login', [RegisterController::class, 'checkLogin'])->name('checkLogin');

    Route::post('reset-password', [UserController::class, 'resetPassword'])->name('reset-password');

    Route::post('verification-email', [UserController::class, 'verificationEmail']);

    Route::post('change-email', [UserController::class, 'changeEmail']);

    Route::post('change-password', [UserController::class, 'changePassword']);

    Route::post('user-info', [UserController::class, 'userInfo']);
});

// Evaluates
Route::prefix('/evaluates')->group(function () {
    Route::get('/', [EvaluatesController::class, 'index']);
    Route::post('/add', [EvaluatesController::class, 'store']);
});

// Favorite
Route::prefix('/favorite')->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('/add', [FavoriteController::class, 'store']);
});

// DeliveryAddress
Route::prefix('/delivery-address')->group(function () {
    Route::get('/', [DeliveryAddressController::class, 'index']);
    Route::post('/add', [DeliveryAddressController::class, 'store']);
    Route::get('/show/{id}', [DeliveryAddressController::class, 'show']);
});

// Order
Route::prefix('/order')->group(function () {
    Route::get('/page={page}&user={user_id}&status={status}', [OrderController::class, 'index']);
    Route::post('/add', [OrderController::class, 'store']);
    Route::get('/detail/{id}', [OrderController::class, 'show']);
});

// Categories
Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
});

// Brands.
Route::prefix('/brands')->group(function () {
    Route::get('/', [BrandController::class, 'index']);
});

// Coupons
Route::prefix('/coupons')->group(function () {
    Route::get('/', [CouponController::class, 'index']);
});

// Carts
Route::prefix('/cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'store']);
    Route::post('/delete', [CartController::class, 'destroy']);
});

// Product
Route::prefix('/product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/detail', [ProductController::class, 'show']);
    Route::get('/get-recommendations/{id}', [ProductController::class, 'getRecommendations']);
});
