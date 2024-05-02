<?php

use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\ShopController;
use App\Http\Controllers\admin\ShopOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('admin')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    //Category
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::middleware('admin')->group(function () {
            Route::resource('/category', CategoryController::class);
            // products
            Route::resource('/product', ProductController::class);
            // shop controller
            Route::resource('/shop', ShopController::class);
            // order Controller
            Route::resource('/orders', OrderController::class);
            // seetting controller
            Route::resource('/setting', SettingController::class);
        });
    });
});

Route::prefix('franchise')->group(function(){
    Route::controller(ShopController::class)->group(function () {
        Route::post('/login', 'login');
    });
    Route::middleware('isshop')->group(function () {
        Route::resource('/order',ShopOrderController::class);
    });
});
