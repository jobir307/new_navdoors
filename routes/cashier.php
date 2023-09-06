<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cashier\CashierController;
use App\Http\Controllers\cashier\InsController;
use App\Http\Controllers\cashier\OutsController;

Route::middleware(['cashier'])->group(function() {
    Route::get('cashier/{date}', [CashierController::class, 'index'])->name('cashier');
    Route::get('ins/{inout_type}', [InsController::class, 'index'])->name('ins');
    Route::post('save-order-payment', [InsController::class, 'store_order_payment'])->name('order-ins');
    Route::get('show-order-payments/{id}', [InsController::class, 'show_order_payments'])->name('show_order_payments');
    Route::post('store-stock-ins', [InsController::class, 'store_stock_ins'])->name('create-stock-ins');
    Route::get('outs/{inout_type}', [OutsController::class, 'index'])->name('outs');
    Route::post('store-stock-outs', [OutsController::class, 'store_stock_outs'])->name('create-stock-outs');
});

