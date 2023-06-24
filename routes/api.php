<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Master\ProductController;
use App\Http\Controllers\Api\Trx\ProductOrderController;
use App\Http\Controllers\Api\Transaction\OrderController;
use App\Http\Controllers\Api\Master\ProductStockController;

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

Route::prefix('v1')->group(function () {

    // Route for /user with UserController and api.user. name
    Route::controller(UserController::class)->prefix('user')->as('api.user.')->group(function (){
        Route::get('', 'findAll')->name('findAll');
        Route::post('', 'create')->name('create');
        Route::get('/{user_id}', 'findOne')->name('findOne');
        Route::patch('/{user_id}', 'updateOne')->name('updateOne');
        Route::put('/{user_id}', 'updateOne')->name('updateOne');
        Route::delete('/{user_id}', 'deleteOne')->name('deleteOne');
    });

    // Route for /produk with ProductController and api.produk. name
    Route::controller(ProductController::class)->prefix('produk')->as('api.produk.')->group(function (){
        Route::get('', 'findAll')->name('findAll');
        Route::post('', 'create')->name('create');
        Route::get('/{produk_id}', 'findOne')->name('findOne');
        Route::patch('/{produk_id}', 'updateOne')->name('updateOne');
        Route::put('/{produk_id}', 'updateOne')->name('updateOne');
        Route::delete('/{produk_id}', 'deleteOne')->name('deleteOne');
    });

    // Route for /produk-stok with ProductStockController and api.produk-stok. name
    Route::controller(ProductStockController::class)->prefix('produk-stok')->as('api.produk-stok.')->group(function (){
        Route::get('', 'findAll')->name('findAll');
        Route::get('/{produk_id}', 'findOne')->name('findOne');
        Route::patch('/{produk_id}', 'updateOne')->name('updateOne');
        Route::put('/{produk_id}', 'updateOne')->name('updateOne');
    });

    // Route for /pesanan with OrderController and api.pesanan. name
    Route::controller(OrderController::class)->prefix('pesanan')->as('api.pesanan.')->group(function (){
        Route::get('', 'findAll')->name('findAll');
        Route::post('', 'create')->name('create');
        Route::get('/{pesanan_id}', 'findOne')->name('findOne');
        Route::patch('/{pesanan_id}', 'updateOne')->name('updateOne');
        Route::put('/{pesanan_id}', 'updateOne')->name('updateOne');
        Route::delete('/{pesanan_id}', 'deleteOne')->name('deleteOne');
    });

    // Route for /pesanan-produk with ProductOrderController and api.pesanan-produk. name
    Route::controller(ProductOrderController::class)->prefix('pesanan-produk')->as('api.pesanan-produk.')->group(function (){
        Route::get('', 'findAll')->name('findAll');
        Route::post('', 'create')->name('create');
        Route::get('/{pesanan_produk_id}', 'findOne')->name('findOne');
        Route::patch('/{pesanan_produk_id}', 'updateOne')->name('updateOne');
        Route::put('/{pesanan_produk_id}', 'updateOne')->name('updateOne');
        Route::delete('/{pesanan_produk_id}', 'deleteOne')->name('deleteOne');
    });
});