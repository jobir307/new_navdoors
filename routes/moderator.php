<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['moderator'])->group(function () {

    Route::get('moderator-home', [App\Http\Controllers\moderator\OrderController::class, 'index'])->name('moderator');
    Route::get('form-outfit/{id}', [App\Http\Controllers\moderator\OrderController::class, 'form_outfit'])->name('form-outfit');
    Route::post('start-process', [App\Http\Controllers\moderator\OrderController::class, 'start_process'])->name('start-process');
    Route::post('set-worker-to-order_process', [App\Http\Controllers\moderator\OrderController::class, 'set_worker'])->name('set-worker');
    Route::post('start-outfit', [App\Http\Controllers\moderator\OrderController::class, 'start_outfit'])->name('start-outfit');
    Route::post('end-outfit', [App\Http\Controllers\moderator\OrderController::class, 'end_outfit'])->name('end-outfit');
    Route::get('order-job-assignment/{id}', [App\Http\Controllers\PDFController::class, 'job_assignment'])->name('order-job-assignment');
    Route::resource('drivers', App\Http\Controllers\moderator\DriverController::class);
    Route::post('moderator-outfit-closed', [App\Http\Controllers\PDFController::class, 'outfit_closed'])->name('outfit-closed');
});

