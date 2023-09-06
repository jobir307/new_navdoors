<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['accountant'])->group(function() {

    Route::get('accountant-orders', [App\Http\Controllers\accountant\OrderController::class, 'index'])->name('accountant');;
    Route::post('order-payment', [App\Http\Controllers\accountant\OrderController::class, 'cashin'])->name('accountant-order-cashin');
    Route::get('show-order-payment-histories/{invoice_id}', [App\Http\Controllers\accountant\OrderController::class, 'payment_histories'])->name('accountant-show-order-payments');
    Route::get('accountant-reconciliation-act', [App\Http\Controllers\accountant\OrderController::class, 'reconciliation_act'])->name('reconciliation-act');
    Route::post('accountant-customer-reconciliation-act', [App\Http\Controllers\accountant\OrderController::class, 'customer_act'])->name('customer-reconciliation-act');
});

