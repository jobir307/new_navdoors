<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['cashier'])->group(function() {
    Route::get('cashier/{date}', [App\Http\Controllers\cashier\CashierController::class, 'index'])->name('cashier');

    Route::get('cashier-order', [App\Http\Controllers\cashier\OrderController::class, 'index'])->name('cashier-order');
    Route::post('save-order-payment', [App\Http\Controllers\cashier\OrderController::class, 'store_order_payment'])->name('order-ins');
    Route::get('show-order-payments/{id}', [App\Http\Controllers\cashier\OrderController::class, 'show_order_payments'])->name('show_order_payments');
    Route::post('set-admin-new-price', [App\Http\Controllers\cashier\OrderController::class, 'set_new_price'])->name('set-admin-new-price-in-cashier');

    Route::post('store-stock-ins', [App\Http\Controllers\cashier\InsController::class, 'store_stock_ins'])->name('create-stock-ins');
    Route::get('ins/{inouttype}', [App\Http\Controllers\cashier\InsController::class, 'index'])->name('ins');

    Route::get('outs/{inout_type}', [App\Http\Controllers\cashier\OutsController::class, 'index'])->name('outs');
    Route::post('store-stock-outs', [App\Http\Controllers\cashier\OutsController::class, 'store_stock_outs'])->name('create-stock-outs');

    Route::get('cashier-workers', [App\Http\Controllers\cashier\WorkerController::class, 'index'])->name('cashier-workers');
    Route::get('cashier-worker-salaries/{worker_id}', [App\Http\Controllers\cashier\WorkerController::class, 'salary'])->name('cashier-worker-salaries');
    Route::get('cashier-show-stock-details/{stock_id}', [App\Http\Controllers\cashier\WorkerController::class, 'show_stock_details'])->name('cashier-show-stock-details');
    Route::post('cashier-pay-worker-salary', [App\Http\Controllers\cashier\WorkerController::class, 'pay_salary'])->name('cashier-pay-worker-salary');

    Route::get('cashier-customers', [App\Http\Controllers\cashier\CustomerController::class, 'index'])->name('cashier-customer');
    Route::get('cashier-customer-shoppings/{customer_id}', [App\Http\Controllers\cashier\CustomerController::class, 'shopping'])->name('customer-shoppings');
});

