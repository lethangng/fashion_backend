<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Brand\BrandController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Color\ColorController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Size\SizeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth
Route::prefix('/')->group(function () {
    // Auth
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'handleLogin']);
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    // Reset password
    Route::get('reset-password', [LoginController::class, 'resetPassword'])->name('reset-password');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.index');

    // Category
    Route::prefix('/category')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/add', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/add', [CategoryController::class, 'store']);
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/edit/{id}', [CategoryController::class, 'update']);
        Route::post('/delete', [CategoryController::class, 'destroy'])->name('category.delete');
        Route::get('/search', [CategoryController::class, 'search'])->name('category.search');
    });

    // Brand
    Route::prefix('/brand')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('brand.index');
        Route::get('/add', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/add', [BrandController::class, 'store']);
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('/edit/{id}', [BrandController::class, 'update']);
        Route::post('/delete', [BrandController::class, 'destroy'])->name('brand.delete');
        Route::get('/search', [BrandController::class, 'search'])->name('brand.search');
    });

    // Color
    Route::prefix('/color')->group(function () {
        Route::get('/', [ColorController::class, 'index'])->name('color.index');
        Route::get('/add', [ColorController::class, 'create'])->name('color.create');
        Route::post('/add', [ColorController::class, 'store']);
        Route::get('/edit/{id}', [ColorController::class, 'edit'])->name('color.edit');
        Route::post('/edit/{id}', [ColorController::class, 'update']);
        Route::post('/delete', [ColorController::class, 'destroy'])->name('color.delete');
        Route::get('/search', [ColorController::class, 'search'])->name('color.search');
    });

    // Size
    Route::prefix('/size')->group(function () {
        Route::get('/', [SizeController::class, 'index'])->name('size.index');
        Route::get('/add', [SizeController::class, 'create'])->name('size.create');
        Route::post('/add', [SizeController::class, 'store']);
        Route::get('/edit/{id}', [SizeController::class, 'edit'])->name('size.edit');
        Route::post('/edit/{id}', [SizeController::class, 'update']);
        Route::post('/delete', [SizeController::class, 'destroy'])->name('size.delete');
        Route::get('/search', [SizeController::class, 'search'])->name('size.search');
    });

    // Users
    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        // Route::get('/add', [UserController::class, 'create'])->name('category.create');
        // Route::post('/add', [UserController::class, 'store']);
        Route::post('/delete', [UserController::class, 'destroy'])->name('user.delete');
        Route::post('/disable', [UserController::class, 'disable'])->name('user.disable');
        Route::post('/search', [UserController::class, 'search'])->name('user.search');
    });

    // Products
    Route::prefix('/product')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('product.index');
        Route::get('/add', [ProductController::class, 'create'])->name('product.add');
        Route::post('/add', [ProductController::class, 'store']);
        // Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        // Route::post('/edit/{id}', [CategoryController::class, 'update']);
        // Route::post('/delete', [CategoryController::class, 'destroy'])->name('category.delete');
        // Route::get('/search', [CategoryController::class, 'search'])->name('category.search');
    });
});


Route::fallback(function () {
    return view('errors.pages-404');
});