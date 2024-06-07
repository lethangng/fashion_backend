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

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::post('login', [AuthController::class, 'login']);
//     Route::post('logout', [AuthController::class, 'logout']);
//     Route::post('refresh', [AuthController::class, 'refresh']);
//     Route::get('profile', [AuthController::class, 'profile']);
// });

// Route::get('/products', [ProductController::class, 'index']);

// Route::post('/products', [ProductController::class, 'upload']);

// Route::post('/product/edit/{id}', [ProductController::class, 'edit']);

// Route::post('/product/delete/{id}', [ProductController::class, 'delete']);

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

Route::prefix('/evaluates')->group(function () {
    Route::post('/add', [EvaluatesController::class, 'store'])->name('evaluates.create');
});

// DeliveryAddress
Route::prefix('/delivery-address')->group(function () {
    Route::post('/add', [DeliveryAddressController::class, 'store']);
    Route::get('/user={user_id}', [DeliveryAddressController::class, 'index']);
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

// Brands
Route::prefix('/brands')->group(function () {
    Route::get('/', [BrandController::class, 'index']);
});

// Coupons
Route::prefix('/coupons')->group(function () {
    Route::get('/{page}', [CouponController::class, 'index']);
});

// Carts
Route::prefix('/cart')->group(function () {
    Route::get('/page={page}&user={user_id}', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'store']);
});

// Product
Route::prefix('/product')->group(function () {
    // Route::get('?page={page}&user={user_id?}&limit={limit?}&new={new?}&sale={sale?}', [ProductController::class, 'index']);
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/detail/{id}', [ProductController::class, 'show']);
    Route::get('/get-recommendations/{id}', [ProductController::class, 'getRecommendations']);
});
