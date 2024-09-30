<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    Laravel\Sanctum\SanctumServiceProvider::class,
    Milon\Barcode\BarcodeServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
];
