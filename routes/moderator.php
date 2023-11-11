<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['moderator'])->group(function () {
    Route::resource('drivers', App\Http\Controllers\moderator\DriverController::class); // haydovchilar

    Route::get('moderator-home', [App\Http\Controllers\moderator\OrderController::class, 'index'])->name('moderator');
    Route::get('order-show/{order_id}', [App\Http\Controllers\moderator\OrderController::class, 'show'])->name('moderator-order-show'); // naryad ma'lumotlarini ko'rish
    Route::get('form-outfit/{order_id}', [App\Http\Controllers\moderator\OrderController::class, 'form_outfit'])->name('form-outfit'); // naryad holatini boshqarish
    Route::post('start-process', [App\Http\Controllers\moderator\OrderController::class, 'start_process'])->name('start-process'); // naryadni proizvodstvoga berish
    Route::post('set-worker-to-order_process', [App\Http\Controllers\moderator\OrderController::class, 'set_worker'])->name('set-worker'); // ishchini naryadga biriktirish
    Route::post('start-outfit', [App\Http\Controllers\moderator\OrderController::class, 'start_outfit'])->name('start-outfit'); // ishchi naryadini boshlash
    Route::post('end-outfit', [App\Http\Controllers\moderator\OrderController::class, 'end_outfit'])->name('end-outfit'); // ishchi naryadini tugatish
    Route::get('door-job-assignment/{order_process_id}', [App\Http\Controllers\PDFController::class, 'door_job_assignment'])->name('door-job-assignment'); // eshikni parametrlari ishchiga 
    Route::get('jamb-job-assignment/{order_process_id}', [App\Http\Controllers\PDFController::class, 'jamb_job_assignment'])->name('jamb-job-assignment'); // nalichnikni parametrlari ishchiga
    Route::get('nsjamb-job-assignment/{order_process_id}', [App\Http\Controllers\PDFController::class, 'nsjamb_job_assignment'])->name('nsjamb-job-assignment'); // nostandart nalichnikni parametrlari ishchiga
    Route::get('transom-job-assignment/{order_process_id}', [App\Http\Controllers\PDFController::class, 'transom_job_assignment'])->name('transom-job-assignment'); // doborni parametrlari ishchiga
    Route::get('jamb-transom-job-assignment/{order_process_id}', [App\Http\Controllers\PDFController::class, 'jamb_transom_job_assignment'])->name('jamb-transom-job-assignment'); // nalichnik+dobor parametrlari ishchiga
    Route::get('crown-boot-cube-job-assignment/{order_process_id}', [App\Http\Controllers\PDFController::class, 'crown_boot_cube_job_assignment'])->name('crown-boot-cube-job-assignment'); // korona+kubik+sapog parametrlari ishchiga

    Route::post('moderator-jamb-outfit-closed', [App\Http\Controllers\PDFController::class, 'jamb_outfit_closed'])->name('jamb-outfit-closed'); // nalichnik nakladnayasi 
    Route::post('moderator-nsjamb-outfit-closed', [App\Http\Controllers\PDFController::class, 'nsjamb_outfit_closed'])->name('nsjamb-outfit-closed'); // nostandart nalichnik nakladnayasi 
    Route::post('moderator-transom-outfit-closed', [App\Http\Controllers\PDFController::class, 'transom_outfit_closed'])->name('transom-outfit-closed'); // dobor nakladnayasi
    Route::post('moderator-jamb-transom-outfit-closed', [App\Http\Controllers\PDFController::class, 'jamb_transom_outfit_closed'])->name('jamb-transom-outfit-closed'); // nalichnik+dobor nakladnayasi
    Route::post('moderator-door-outfit-closed', [App\Http\Controllers\PDFController::class, 'door_outfit_closed'])->name('door-outfit-closed'); // eshik nakladnayasi
    Route::post('moderator-crown-boot-cube-outfit-closed', [App\Http\Controllers\PDFController::class, 'crownbootcube_outfit_closed'])->name('crownbootcube-outfit-closed'); // korona+sapog+kubik nakladnayasi
    
    Route::post('redirect-back-order', [App\Http\Controllers\moderator\OrderController::class, 'redirect_back_order'])->name('redirect-order-to-manager'); // naryadni sotuv bo'limiga qaytarish

    Route::post('door-show-pdf', [App\Http\Controllers\PDFController::class, 'moderator_doorshow_pdf'])->name('door-show-pdf'); // eshik naryadi parametrlarini ko'rish pdf
    Route::post('jamb-show-pdf', [App\Http\Controllers\PDFController::class, 'moderator_jambshow_pdf'])->name('jamb-show-pdf'); // nalichnik naryadi parametrlarini ko'rish pdf
    Route::post('nsjamb-show-pdf', [App\Http\Controllers\PDFController::class, 'moderator_nsjambshow_pdf'])->name('nsjamb-show-pdf'); // nostandart nalichnik naryadi parametrlarini ko'rish pdf
    Route::post('transom-show-pdf', [App\Http\Controllers\PDFController::class, 'moderator_transomshow_pdf'])->name('transom-show-pdf'); // dobor naryadi parametrlarini ko'rish pdf
    Route::post('jamb-transom-show-pdf', [App\Http\Controllers\PDFController::class, 'moderator_jambtransomshow_pdf'])->name('jamb-transom-show-pdf'); // nalichnik+dobor naryad parametrlarini ko'rish pdf
    Route::post('crown-boot-cube-show-pdf', [App\Http\Controllers\PDFController::class, 'moderator_crownbootcubeshow_pdf'])->name('crown-boot-cube-show-pdf'); // korona+sapog+kubik naryad parametrlarini ko'rish pdf

    Route::get('workers-list', [App\Http\Controllers\moderator\WorkerController::class, 'index'])->name('moderator-workers'); // ishchilar ro'yxati
    Route::get('worker-salaries/{worker_id}', [App\Http\Controllers\moderator\WorkerController::class, 'salary'])->name('worker-salaries'); // ishchining oyligi
    Route::get('show-stock-details/{stock_id}', [App\Http\Controllers\moderator\WorkerController::class, 'show_stock_details'])->name('show-stock-details');

    Route::get('moderator-glass-figures', [App\Http\Controllers\moderator\GlassFigureController::class, 'index'])->name('moderator-glass-figures'); // shisha shakllari
    Route::post('pay-worker-salary', [App\Http\Controllers\moderator\WorkerController::class, 'pay_salary'])->name('pay-worker-salary'); // ishchi oyligini to'lash 

    Route::post('moderator-create-waybill', [App\Http\Controllers\PDFController::class, 'moderator_orderwaybill_pdf'])->name('moderator-create-waybill'); // nakladnaya yaratish (chastichno yuk jo'natish)
    Route::post('moderator-order-close', [App\Http\Controllers\moderator\OrderController::class, 'order_closed'])->name('moderator-order-close'); // naryadni yakunlash
    Route::get('moderator-waybill-show/{waybill_id}', [App\Http\Controllers\moderator\OrderController::class, 'waybill_show'])->name('moderator-waybill-show'); // yuk xati ma'lumotlarini ko'rish
});