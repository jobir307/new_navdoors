<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['manager'])->group(function() {
    Route::resources([
        'order-doors'            => App\Http\Controllers\manager\order\DoorController::class, // naryadlar (eshik)
        'order-jambs'            => App\Http\Controllers\manager\order\JambController::class, // naryadlar (nalichnik)
        'order-transoms'         => App\Http\Controllers\manager\order\TransomController::class, // naryadlar (dobor)
        'order-jambs-transoms'   => App\Http\Controllers\manager\order\JambTransomController::class, // naryadlar (nalichnik + dobor)
        'manager-customers'      => App\Http\Controllers\manager\ManagerCustomerController::class
    ]);

    Route::get('orders', [App\Http\Controllers\manager\OrderController::class, 'index'])->name('orders');;
    Route::post('confirm-order-invoice', [App\Http\Controllers\manager\OrderController::class, 'confirm_invoice'])->name('confirm-invoice');
    Route::post('reload-jamb-by-doortype', [App\Http\Controllers\manager\OrderController::class, 'jamb_by_doortype'])->name('reload-jamb');
    Route::post('get-glass-types', [App\Http\Controllers\manager\OrderController::class, 'glass_types'])->name('get-glasstypes');
    Route::post('region-districts', [App\Http\Controllers\manager\ManagerRegionController::class, 'get_district'])->name('get-region-districts');
    Route::post('district-mahalla', [App\Http\Controllers\manager\ManagerRegionController::class, 'get_mahalla'])->name('get-district-mahalla');
    Route::post('mahalla-street', [App\Http\Controllers\manager\ManagerRegionController::class, 'get_street'])->name('get-mahalla-streets');

    Route::get('pdf-order-door/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_door']);
    Route::get('pdf-order-jamb/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_jamb']);
    Route::get('pdf-order-transom/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_transom']);
    Route::get('pdf-order-jamb-transom/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_jamb_transom']);
});

