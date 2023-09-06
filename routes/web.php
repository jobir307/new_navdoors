<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('not-found', function() {
    return view('404');
})->name('404');


// oilakredit.uz regionlarini olish
Route::get('regions-data', [App\Http\Controllers\RegionsDataController::class, 'index']);

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/manager.php';
require __DIR__.'/cashier.php';
require __DIR__.'/moderator.php';
require __DIR__.'/accountant.php';