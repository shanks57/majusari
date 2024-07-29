<?php

use App\Http\Controllers\Api\GoodsController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EtalaseController;
use App\Http\Controllers\GoodSafeController;
use App\Http\Controllers\GoodShowcaseController;
use App\Http\Controllers\GoodsTypeController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\GoodTrayController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.dashboard');
});

Route::get('/master/showcases', [EtalaseController::class, 'index'])->name("master-showcase");

// master type
Route::get('/master/types', [GoodsTypeController::class, 'index'])->name('master.types');
Route::post('/goods-types/store', [GoodsTypeController::class, 'store'])->name('master.types.store');
Route::put('/goods-types/{id}/update', [GoodsTypeController::class, 'update'])->name('master.types.update');
Route::delete('/goods-types/{id}', [GoodsTypeController::class, 'destroy'])->name('master.destroy');
// end route master tipe

Route::get('/master/brands', [BrandController::class, 'index'])->name("master-brand");

Route::get('/master/customers', [CustomerController::class, 'index'])->name("master-customer");

Route::get('/master/employees', [UserController::class, 'index'])->name("master-employee");

Route::get('/goods/showcases', [GoodShowcaseController::class, 'index'])->name("goods.showcase");
Route::patch('/goods/{id}/move-to-safe', [GoodShowcaseController::class, 'moveToSafe'])
    ->name('goods.moveToSafe');
Route::delete('/goods/{id}/showcases', [GoodShowcaseController::class, 'destroy'])->name('goods-showcase.destroy');
Route::get('/goods/{id}/print-barcode', [GoodShowcaseController::class, 'printBarcode'])->name('goods-showcase.printBarcode');


Route::get('/goods/safe', [GoodSafeController::class, 'index'])->name("goods.safe");
Route::patch('/goods/{id}/move-to-showcase', [GoodSafeController::class, 'moveToShowcase'])->name('goods.moveToShowcase');
Route::delete('/goods/{id}/showcases', [GoodShowcaseController::class, 'destroy'])->name('goods-showcase.destroy');

Route::get('/goods/trays', [GoodTrayController::class, 'index'])->name("/goods/tray");
Route::get('/goods/trays/{id}', [GoodTrayController::class, 'find'])->name("find-goods-tray");


Route::get('/sales', [SalesController::class, 'index'])->name("sale.index");

Route::get('/profile', function () {
    return view('pages.profile');
});