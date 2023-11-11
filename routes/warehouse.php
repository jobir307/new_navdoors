<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['warehouse'])->group(function() {
    Route::get('warehouse-dashboard', [App\Http\Controllers\warehouse\HomeController::class, 'dashboard'])->name('warehouse');
    Route::resources([
        'warehouse-products' => App\Http\Controllers\warehouse\product\ProductController::class, // Tovarlar
        'warehouse-sales'    => App\Http\Controllers\warehouse\product\SaleController::class, // Sotuvlar
        'warehouse-incomes'  => App\Http\Controllers\warehouse\product\IncomeController::class, // Kirimlar
        'warehouse-orders'   => App\Http\Controllers\warehouse\product\OrderController::class, // Zakazlar
    ]);
});

