<?php

use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\Api\GoodsController;
use App\Http\Controllers\Api\GoodsTypeController;
use App\Http\Controllers\Api\MerkController;
use App\Http\Controllers\Api\ShowcaseController;
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
