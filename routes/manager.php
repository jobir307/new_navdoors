<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['manager'])->group(function() {
    Route::resources([
        'order-doors'            => App\Http\Controllers\manager\order\DoorController::class, // naryadlar (eshik)
        'order-jambs'            => App\Http\Controllers\manager\order\JambController::class, // naryadlar (nalichnik)
        'order-nsjambs'          => App\Http\Controllers\manager\order\NSJambController::class, // naryadlar (nostandart nalichnik)
        'order-transoms'         => App\Http\Controllers\manager\order\TransomController::class, // naryadlar (dobor)
        'order-jambs-transoms'   => App\Http\Controllers\manager\order\JambTransomController::class, // naryadlar (nalichnik + dobor)
        'order-ccbjs'            => App\Http\Controllers\manager\order\CCBJController::class, // naryadlar (korona + kubik + sapog + nalichnik)
        'manager-customers'      => App\Http\Controllers\manager\ManagerCustomerController::class // xaridorlar
    ]);

    Route::get('orders', [App\Http\Controllers\manager\OrderController::class, 'index'])->name('orders');
    Route::get('manager-glasses', [App\Http\Controllers\manager\HomeController::class, 'glasses'])->name('manager-glasses');
    Route::get('manager-doors', [App\Http\Controllers\manager\HomeController::class, 'doors'])->name('manager-doors');
    Route::post('set-new-contract-price', [App\Http\Controllers\manager\OrderController::class, 'set_new_order_price'])->name('admin-set-new-contract_price'); // admin naryadga yangi narx kiritadi.
    Route::post('confirm-order-invoice', [App\Http\Controllers\manager\OrderController::class, 'confirm_invoice'])->name('confirm-invoice');
    Route::post('get-glass-types', [App\Http\Controllers\manager\OrderController::class, 'glass_types'])->name('get-glasstypes');
    Route::post('region-districts', [App\Http\Controllers\manager\ManagerRegionController::class, 'get_district'])->name('get-region-districts');
    Route::post('district-mahalla', [App\Http\Controllers\manager\ManagerRegionController::class, 'get_mahalla'])->name('get-district-mahalla');
    Route::post('mahalla-street', [App\Http\Controllers\manager\ManagerRegionController::class, 'get_street'])->name('get-mahalla-streets');

    Route::get('pdf-order-door/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_door']);
    Route::get('pdf-order-jamb/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_jamb']);
    Route::get('pdf-order-nsjamb/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_nsjamb']);
    Route::get('pdf-order-transom/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_transom']);
    Route::get('pdf-order-jamb-transom/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_jamb_transom']);
    Route::get('pdf-order-ccbj/{id}', [App\Http\Controllers\PDFController::class, 'manager_customer_order_ccbj']);

    Route::post('delete-order', [App\Http\Controllers\manager\OrderController::class, 'delete'])->name('admin-delete-order');
});