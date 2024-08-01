<?php

use App\Http\Controllers\API\GoodsController;
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

Route::get('/master/showcases', [EtalaseController::class, 'index'])->name("master.showcase");
Route::post('/master-showcase/store', [EtalaseController::class, 'store'])->name('master.showcase.store');
Route::post('/master/showcase/add-trays', [EtalaseController::class, 'addTrays'])->name('master.showcase.add-trays');
Route::delete('/master-showcase/{id}', [EtalaseController::class, 'destroy'])->name('master.showcase.destroy');

// master type
Route::get('/master/types', [GoodsTypeController::class, 'index'])->name('master.types');
Route::post('/master-types/store', [GoodsTypeController::class, 'store'])->name('master.types.store');
Route::put('/master-types/{id}/update', [GoodsTypeController::class, 'update'])->name('master.types.update');
Route::delete('/master-types/{id}', [GoodsTypeController::class, 'destroy'])->name('master.types.destroy');
// end route master type

// master merk/brand
Route::get('/master/brands', [BrandController::class, 'index'])->name('master-brands');
Route::post('/master-brands/store', [BrandController::class, 'store'])->name('master.brands.store');
Route::put('/master-brands/{id}/update', [BrandController::class, 'update'])->name('master.brands.update');
Route::delete('/master-brands/{id}', [BrandController::class, 'destroy'])->name('master.brands.destroy');
// end route master merk/brand

Route::get('/master/customers', [CustomerController::class, 'index'])->name("master-customer");

// route master emplyee
Route::get('/master/employees', [UserController::class, 'index'])->name("master.employees");
Route::post('/master-employees/store', [UserController::class, 'store'])->name('master.employees.store');
Route::put('/master-employees/{id}/update', [UserController::class, 'update'])->name('master.employees.update');
Route::put('/master-employees/{id}/set-password', [UserController::class, 'setPassword'])->name('master.employees.set-password');
Route::put('/employees/{id}/reset-password', [UserController::class, 'resetPassword'])->name('employees.reset-password');
// end route master emplyee

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

Route::get('/login', function () {
    return view('pages.login');
});

Route::get('/forgot-password', function () {
    return view('pages.forgot-password');
});


Route::get('/reset-password', function () {
    return view('pages.reset-password');
});
