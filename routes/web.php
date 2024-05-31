<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\Size\SizeController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Brand\BrandController;
use App\Http\Controllers\Admin\Color\ColorController;
use App\Http\Controllers\Admin\Coupon\CouponController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Evaluates\EvaluatesController;
use App\Http\Controllers\Admin\Product\ProductPriceController;

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
        Route::get('/page/{page?}', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/add', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/add', [CategoryController::class, 'store']);
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/edit/{id}', [CategoryController::class, 'update']);
        Route::post('/delete', [CategoryController::class, 'destroy'])->name('category.delete');
        Route::get('/search', [CategoryController::class, 'search'])->name('category.search');
    });

    // Brand
    Route::prefix('/brand')->group(function () {
        Route::get('/page/{page?}', [BrandController::class, 'index'])->name('brand.index');
        Route::get('/add', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/add', [BrandController::class, 'store']);
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('/edit/{id}', [BrandController::class, 'update']);
        Route::post('/delete', [BrandController::class, 'destroy'])->name('brand.delete');
        Route::get('/search', [BrandController::class, 'search'])->name('brand.search');
    });

    // Color
    Route::prefix('/color')->group(function () {
        Route::get('/page/{page?}', [ColorController::class, 'index'])->name('color.index');
        Route::get('/add', [ColorController::class, 'create'])->name('color.create');
        Route::post('/add', [ColorController::class, 'store']);
        Route::get('/edit/{id}', [ColorController::class, 'edit'])->name('color.edit');
        Route::post('/edit/{id}', [ColorController::class, 'update']);
        Route::post('/delete', [ColorController::class, 'destroy'])->name('color.delete');
        Route::get('/search', [ColorController::class, 'search'])->name('color.search');
    });

    // Size
    Route::prefix('/size')->group(function () {
        Route::get('/page/{page?}', [SizeController::class, 'index'])->name('size.index');
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
        Route::get('/page/{page?}', [ProductController::class, 'index'])->name('product.index');
        Route::get('/add', [ProductController::class, 'create'])->name('product.create');
        Route::post('/add', [ProductController::class, 'store']);
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/edit/{id}', [ProductController::class, 'update']);
        Route::post('/delete', [ProductController::class, 'destroy'])->name('product.delete');
        Route::get('/search', [ProductController::class, 'search'])->name('product.search');
    });

    // Product price
    Route::prefix('/product-price')->group(function () {
        Route::get('/product/{product_id}/page/{page?}', [ProductPriceController::class, 'index'])->name('product_price.index');
        Route::get('/add/product/{product_id}', [ProductPriceController::class, 'create'])->name('product_price.create');
        Route::post('/add/product/{product_id}', [ProductPriceController::class, 'store']);
        Route::get('/edit/{id}', [ProductPriceController::class, 'edit'])->name('product_price.edit');
        Route::post('/edit/{id}', [ProductPriceController::class, 'update']);
        Route::post('/delete', [ProductPriceController::class, 'destroy'])->name('product_price.delete');
        Route::get('/search', [ProductPriceController::class, 'search'])->name('product_price.search');
    });


    // Coupon
    Route::prefix('/coupon')->group(function () {
        Route::get('/page/{page?}', [CouponController::class, 'index'])->name('coupon.index');
        Route::get('/add', [CouponController::class, 'create'])->name('coupon.create');
        Route::post('/add', [CouponController::class, 'store']);
        Route::get('/edit/{id}', [CouponController::class, 'edit'])->name('coupon.edit');
        Route::post('/edit/{id}', [CouponController::class, 'update']);
        Route::post('/delete', [CouponController::class, 'destroy'])->name('coupon.delete');
        Route::get('/search', [CouponController::class, 'search'])->name('coupon.search');
    });

    // Evaluates
    Route::prefix('/evaluates')->group(function () {
        Route::get('/page/{page?}', [EvaluatesController::class, 'index'])->name('evaluate.index');
        // Route::get('/show/{id}', [EvaluatesController::class, 'show'])->name('evaluate.show');
        Route::post('/edit', [EvaluatesController::class, 'update'])->name('evaluate.update');
        Route::post('/delete', [EvaluatesController::class, 'destroy'])->name('evaluate.delete');
        Route::get('/search', [EvaluatesController::class, 'search'])->name('evaluate.search');
    });
});


Route::fallback(function () {
    return view('errors.pages-404');
});
