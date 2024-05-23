<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\RegisterController;

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

    Route::post('check-login', [UserController::class, 'checkLogin'])->name('checkLogin');

    Route::get('reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
});
