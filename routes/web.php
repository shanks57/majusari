<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EtalaseController;
use App\Http\Controllers\GoodSafeController;
use App\Http\Controllers\GoodShowcaseController;
use App\Http\Controllers\GoodsTypeController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\GoodTrayController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {
    // start dashboard
    Route::get('/', [DashboardController::class, 'index'])->name("dashboard-page");
    Route::post('/kurs-emas/store', [DashboardController::class, 'updateKurs'])->name('dashboard.kurs.update');
    Route::get('/chart-data', [DashboardController::class, 'getChartData']);
    Route::get('/detail-sale-summary', [DashboardController::class, 'getChartSalesSummaryDetail']);
    Route::get('/get-weight-chart-data', [DashboardController::class, 'getWeightChartData']);

    Route::get('/get-total-weight-by-type', [DashboardController::class, 'getTotalWeightByType']);
    // Untuk API
    Route::get('/get-weight-by-brand', [DashboardController::class, 'getTotalWeightByBrand']);
    Route::get('/get-weight-by-gold-rate', [DashboardController::class, 'getTotalWeightByGoldRate']);

    // end dashboard

    Route::group(['middleware' => ['role:superadmin']], function () {
        
        // master/showcases
            Route::get('/master/showcases', [EtalaseController::class, 'index'])->name("master.showcase");
            Route::post('/master-showcase/store', [EtalaseController::class, 'store'])->name('master.showcase.store');
            Route::post('/master/showcase/add-trays', [EtalaseController::class, 'addTrays'])->name('master.showcase.add-trays');
            Route::delete('/master-showcase/{id}', [EtalaseController::class, 'destroy'])->name('master.showcase.destroy');

            Route::get('/master/showcase/export-pdf', [EtalaseController::class, 'downloadPdf'])->name('master.showcase.export-pdf');
            Route::get('/master/showcase/export-excel', [EtalaseController::class, 'exportExcel'])->name('master.showcase.export-excel');
            Route::get('/master/showcase/print', [EtalaseController::class, 'print'])->name('master.showcase.print');
        // end master/showcases

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

        // start route /master/customers
            Route::get('/master/customers', [CustomerController::class, 'index'])->name("master-customer");
            Route::get('/customer/export-pdf', [CustomerController::class, 'downloadPdf'])->name('master.customer.export-pdf');
            Route::get('/customer/export-excel', [CustomerController::class, 'exportExcel'])->name('master.customer.export-excel');
            Route::get('/customer/print', [CustomerController::class, 'print'])->name('master.customer.print');
        // end route /master/customers

        // route master employee
            Route::get('/master/employees', [UserController::class, 'index'])->name("master.employees");
            Route::post('/master-employees/store', [UserController::class, 'store'])->name('master.employees.store');
            Route::put('/master-employees/{id}/update', [UserController::class, 'update'])->name('master.employees.update');
            Route::put('/master-employees/{id}/set-password', [UserController::class, 'setPassword'])->name('master.employees.set-password');
            Route::put('/employees/{id}/reset-password', [UserController::class, 'resetPassword'])->name('employees.reset-password');

            Route::get('/employees/export-pdf', [UserController::class, 'downloadPdf'])->name('master.employees.export-pdf');
            Route::get('/employees/export-excel', [UserController::class, 'exportExcel'])->name('master.employees.export-excel');
            Route::get('/employees/print', [UserController::class, 'print'])->name('master.employees.print');
        // end route master employee

        // start route /goods/safe
            Route::get('/goods/safe', [GoodSafeController::class, 'index'])->name("goods.safe");
            Route::post('/goods/safe-store', [GoodSafeController::class, 'store'])->name('goods.safeStore');
            Route::patch('/goods-safe/{id}/update', [GoodSafeController::class, 'update'])->name('goods.safeUpdate');
            Route::patch('/goods/{id}/move-to-showcase', [GoodSafeController::class, 'moveToShowcase'])->name('goods.moveToShowcase');
            Route::delete('/goods/{id}/safe', [GoodSafeController::class, 'destroy'])->name('goods-safe.destroy');
            Route::get('/goods/{id}/safe-print-barcode', [GoodSafeController::class, 'printBarcode'])->name('safe-showcase.printBarcode');

            Route::get('/goods/safe/export-pdf/{uniqueCode}', [GoodSafeController::class, 'downloadPdf'])->name('goods.safe.export-pdf');
            Route::get('/goods/safe/export-excel{uniqueCode}', [GoodSafeController::class, 'exportExcel'])->name('goods.safe.export-excel');
            Route::get('/goods/safe/print', [GoodSafeController::class, 'print'])->name('goods.safe.print');
        // end route /goods/safe

        // start route detail baki
            Route::get('/goods/trays', [GoodTrayController::class, 'index'])->name("/goods/tray");
            Route::get('/goods/trays/{id}', [GoodTrayController::class, 'find'])->name("find-goods-tray");
            Route::post('/goods/trays-store', [GoodTrayController::class, 'store'])->name('goods.trayStore');
            Route::patch('/goods-trays/{id}/move-to-safe', [GoodTrayController::class, 'moveToSafe'])
                ->name('goods-tray.moveToSafe');
            Route::post('/goods-traysgoods-trays/slot/update', [GoodTrayController::class, 'updateSlotBaki'])->name('traysgoods.slot.update');
            Route::delete('/traysgoods/baki/delete/{id}', [GoodTrayController::class, 'deleteTrayAndGoods'])->name('traysgoods.baki.delete');
        // end route detail baki
        
        // start notif
            Route::get('/notification', [SalesController::class, 'getNotification'])->name('notification');
            Route::patch('/cart/{notif}/reject-price', [SalesController::class, 'rejectPrice'])->name('cart.reject-price');
            Route::patch('/cart/{notif}/agree-price', [SalesController::class, 'agreePrice'])->name('cart.agree-price');
        // end notif
        
    });

    // start route /goods/showcases
        Route::get('/goods/showcases', [GoodShowcaseController::class, 'index'])->name("goods.showcase");
        Route::post('/goods/showcases-store', [GoodShowcaseController::class, 'store'])->name('goods.showcaseStore');
        Route::patch('/goods-showcase/{id}/update', [GoodShowcaseController::class, 'update'])->name('goods.showcaseUpdate');
        Route::patch('/goods/{id}/move-to-safe', [GoodShowcaseController::class, 'moveToSafe'])->name('goods.moveToSafe');
        Route::delete('/goods/{id}/showcases', [GoodShowcaseController::class, 'destroy'])->name('goods-showcase.destroy');
        Route::get('/goods/{id}/print-barcode', [GoodShowcaseController::class, 'printBarcode'])->name('goods-showcase.printBarcode');
        Route::get('/goods/showcases/export-pdf', [GoodShowcaseController::class, 'downloadPdf'])->name('goods.showcase.export-pdf');
        Route::get('/goods/showcases/export-excel', [GoodShowcaseController::class, 'exportExcel'])->name('goods.showcase.export-excel');
        Route::get('/goods/showcases/print', [GoodShowcaseController::class, 'print'])->name('goods.showcase.print');
    // end route /goods/showcases

    // route penjualan
        Route::get('/sales', [SalesController::class, 'index'])->name("sale.index");
        Route::post('/sale/search-code', [SalesController::class, 'searchCode'])->name('sale.search-code');
        Route::post('/sale/insert-to-cart', [SalesController::class, 'insertToCart'])->name('sale.insert-to-cart');
        Route::post('/cart/insert-to-cart', [SalesController::class, 'insertToCartInChart'])->name('cart.insert-to-cart');
        Route::get('/cart', [SalesController::class, 'cart'])->name("pages.cart");
        Route::delete('/cart/{id}', [SalesController::class, 'destroy'])->name('cart.destroy');
        Route::patch('/cart/{id}/update', [SalesController::class, 'update'])->name('cart.update');
        Route::patch('/cart/{id}/add-complaint', [SalesController::class, 'addComplaint'])->name('cart.add-complaint');
        Route::post('/checkout', [SalesController::class, 'checkout'])->name('sale.checkout');
        Route::post('/sale/search-nota', [SalesController::class, 'searchNota'])->name('sale.search-nota');
        Route::get('/sale/{id}/print-nota', [SalesController::class, 'printNota'])->name('sale.printNota');
        Route::get('/sales/export', [SalesController::class, 'export'])->name('sales.export');
    // end route penjualan

    // start profile
        Route::get('/profile', function () {
            return view('pages.profile');
        })->name('pages.profile');
        Route::put('/profile/update/{id}', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/{id}/set-password', [UserController::class, 'updatePassword'])->name('profile.set-password');
    // end profile
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::get('/symlink', function () {
    Artisan::call('storage:link');
    echo 'ok';
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');
