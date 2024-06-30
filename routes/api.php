<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\GoodsController;
use App\Http\Controllers\Api\GoodsTypeController;
use App\Http\Controllers\Api\MerkController;
use App\Http\Controllers\Api\ShowcaseController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\Api\TrayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('customers', CustomerController::class);
Route::get('customers-data/search', [CustomerController::class, 'search']);

Route::apiResource('merks', MerkController::class);
Route::get('merks-data/search', [MerkController::class, 'search']);

Route::apiResource('goods-types', GoodsTypeController::class);
Route::get('goodsTypes/search', [GoodsTypeController::class, 'search']);

Route::apiResource('trays', TrayController::class);

Route::apiResource('showcases', ShowcaseController::class);
Route::get('showcase/search', [ShowcaseController::class, 'search']);

Route::apiResource('goods', GoodsController::class);

Route::apiResource('employees', EmployeeController::class);
Route::get('employees-data/search', [EmployeeController::class, 'search']);

Route::apiResource('transactions', TransactionController::class);
Route::get('transaction/search', [TransactionController::class, 'search']);
Route::get('transaction/search-by-nota', [TransactionController::class, 'getByCode']);
Route::get('transaction/search-goods-by-barcode', [TransactionController::class, 'getGoodsByBarcode']);
Route::get('transaction/grouped-by-date', [TransactionController::class, 'indexWithGoodsGroupedByDate']);
Route::post('transaction/add-transaction', [TransactionController::class, 'createTransaction']);

Route::post('cart/add', [CartController::class, 'add']);
Route::delete('cart/{cartId}', [CartController::class, 'remove']);
Route::get('cart/{userId}', [CartController::class, 'getCart']);
