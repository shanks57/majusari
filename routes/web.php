<?php

use App\Http\Controllers\EtalaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.dashboard');
});

Route::get('/master/showcases', [EtalaseController::class, 'index'])->name("master-showcase");

Route::get('/master/types', function () {
    return view('pages.master-types');
});

Route::get('/master/brands', function () {
    return view('pages.master-brands');
});

Route::get('/master/customers', function () {
    return view('pages.master-customers');
});

Route::get('/master/employees', function () {
    return view('pages.master-employees');
});

Route::get('/goods/showcases', function () {
    return view('pages.goods-showcases');
});

Route::get('/goods/trays', function () {
    return view('pages.goods-trays');
});

Route::get('/goods/safe', function () {
    return view('pages.goods-safe');
});

Route::get('/sales', function () {
    return view('pages.sales');
});

Route::get('/profile', function () {
    return view('pages.profile');
});