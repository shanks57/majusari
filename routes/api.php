<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\GoldRateController;
use App\Http\Controllers\API\GoodsController;
use App\Http\Controllers\API\GoodsTypeController;
use App\Http\Controllers\API\MerkController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\SafeStorageController;
use App\Http\Controllers\API\ShowcaseController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\TrayController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/not-authorized', function (Request $request) {
    return response()->json([
        "error" => true,
        "message" => "Unauthorized. Please log in to access this resource.",
        "code" => 401
    ], 401);
})->name('not.authorized');

Route::middleware('auth:sanctum')->group(function () {

    // auth
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/get-user-logged', [AuthController::class, 'getUserLogged']);
    
    Route::put('/profile/{id}', [UserController::class, 'updateProfile']);
    Route::put('/profile/{id}/password', [UserController::class, 'updatePassword']);

    Route::get('/user', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'phone' => $user->phone,
            'debt_receipt' => $user->debt_receipt,
            'wages' => $user->wages,
            'address' => $user->address,
            'status' => $user->status,
            'role' => $user->getRoleNames()->first()
        ]);
    });
    Route::get('/user/{id}', [UserController::class, 'show']);

    Route::apiResource('customers', CustomerController::class);
    Route::get('customers-data/search', [CustomerController::class, 'search']);

    Route::apiResource('merks', MerkController::class);
    Route::get('merks-data/search', [MerkController::class, 'search']);

    Route::apiResource('goods-types', GoodsTypeController::class);
    Route::get('goodsTypes/search', [GoodsTypeController::class, 'search']);

    Route::apiResource('trays', TrayController::class);

    // etalase
    Route::apiResource('showcases', ShowcaseController::class);
    Route::get('showcase/search', [ShowcaseController::class, 'search']);

    // brangkas
    Route::apiResource('showcases', ShowcaseController::class);
    Route::get('showcase/search', [ShowcaseController::class, 'search']);

    // goods
    Route::apiResource('goods', GoodsController::class);
    Route::get('goods-data/search', [GoodsController::class, 'search']);
    Route::get('goods/{id}/image', [GoodsController::class, 'showImage']);
    Route::get('goods/{id}/barcode', [GoodsController::class, 'generateBarcode']);

    // goods in safe storage
    Route::apiResource('safe-storage', SafeStorageController::class);
    Route::get('safe-storage-data/search', [SafeStorageController::class, 'search']);
    Route::get('safe-storage/{id}/image', [SafeStorageController::class, 'showImage']);
    Route::get('safe-storage/{id}/barcode', [SafeStorageController::class, 'generateBarcode']);

    // transaction
    Route::apiResource('transactions', TransactionController::class);
    Route::get('transaction/search', [TransactionController::class, 'search']);
    Route::get('transaction/search-by-nota', [TransactionController::class, 'getByCode']);
    Route::get('transaction/search-goods-by-barcode', [TransactionController::class, 'getGoodsByBarcode']);
    Route::get('transaction/grouped-by-date', [TransactionController::class, 'indexWithGoodsGroupedByDate']);
    Route::post('transaction/add-transaction', [TransactionController::class, 'createTransaction']);
    Route::post('/transactions/search-nota', [TransactionController::class, 'searchNota']);

    // cart
    Route::post('cart/add', [CartController::class, 'add']);
    Route::delete('cart/{cartId}', [CartController::class, 'remove']);
    Route::get('cart/{userId}', [CartController::class, 'getCart']);
    Route::get('/search-code/{code}', [CartController::class, 'searchCode']);
    Route::put('/cart/{id}/update-selling-price', [CartController::class, 'updateSellingPrice']);
    Route::put('/cart/{id}/add-complaint', [CartController::class, 'addComplaint']);
    // end chart

    // start notif
    Route::get('/notifications', [NotificationController::class, 'getNotification']);
    Route::post('/notifications/{notifId}/verify-price-status', [NotificationController::class, 'verifyPriceStatus']);

    // end notif

    // dashboard
    Route::apiResource('dashboard/gold-rates', GoldRateController::class);
    Route::get('dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('dashboard/sales-summary', [DashboardController::class, 'getSalesSummary']);
    Route::get('dashboard/goods-summary', [DashboardController::class, 'getGoodsSummary']);

    Route::get('sales/filter', [TransactionController::class, 'filterSalesByDate']);

    Route::get('/sales/export-excel', [TransactionController::class, 'exportExcel']);
    Route::get('/sales/export-pdf', [TransactionController::class, 'exportPDF']);
    Route::get('sales/{id}/print-nota', [TransactionController::class, 'printNota']);

    Route::get('/goods/showcases/export-pdf', [GoodsController::class, 'downloadPdf']);
 
});
//    Route::get('/goods/showcases/export-pdf', [GoodsController::class, 'downloadPdf']);


    Route::get('/goods/showcases/export-excel', [GoodsController::class, 'exportExcel']);

    Route::get('/goods/safe/export-pdf', [SafeStorageController::class, 'downloadPdf']);
    Route::get('/goods/safe/export-excel', [SafeStorageController::class, 'exportExcel']);
Route::get('sales/{id}/print-nota', [TransactionController::class, 'printNota']);