<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EtalaseController;
use App\Http\Controllers\GoodSafeController;
use App\Http\Controllers\GoodShowcaseController;
use App\Http\Controllers\GoodsTypeController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.dashboard');
});

Route::get('/master/showcases', [EtalaseController::class, 'index'])->name("master-showcase");

Route::get('/master/types', [GoodsTypeController::class, 'index'])->name("master-type");

Route::get('/master/brands', [BrandController::class, 'index'])->name("master-brand");

Route::get('/master/customers', [CustomerController::class, 'index'])->name("master-customer");

Route::get('/master/employees', [UserController::class, 'index'])->name("master-employee");

Route::get('/goods/showcases', [GoodShowcaseController::class, 'index'])->name("/goods/showcase");

Route::get('/goods/safe', [GoodSafeController::class, 'index'])->name("/goods/safe");

Route::get('/goods/trays', function () {
    return view('pages.goods-trays');
});

Route::get('/sales', [SalesController::class, 'index'])->name("sale.index");

Route::get('/profile', function () {
    return view('pages.profile');
});