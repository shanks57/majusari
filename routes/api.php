<?php

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
Route::apiResource('merks', MerkController::class);
Route::apiResource('goods-types', GoodsTypeController::class);
Route::apiResource('trays', TrayController::class);
Route::apiResource('showcases', ShowcaseController::class);
Route::apiResource('goods', GoodsController::class);
Route::apiResource('employees', EmployeeController::class);

Route::apiResource('transactions', TransactionController::class);
Route::post('transaction/code', [TransactionController::class, 'getByCode']);
Route::post('transaction/barcode', [TransactionController::class, 'getGoodsByBarcode']);
Route::get('transaction/grouped-by-date', [TransactionController::class, 'indexWithGoodsGroupedByDate']);
