<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['administrator'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\admin\AdminController::class, 'index'])->name('dashboard');
    Route::resources([
        'users'          => App\Http\Controllers\admin\UserController::class, // foydalanuvchilar
        'depths'         => App\Http\Controllers\admin\DepthController::class, // qalinliklar 
        'layers'         => App\Http\Controllers\admin\LayerController::class, // tabaqalar
        'jobs'           => App\Http\Controllers\admin\JobController::class, // lavozimlar
        'doortypes'      => App\Http\Controllers\admin\DoortypeController::class, // eshik turlari
        'framogatypes'   => App\Http\Controllers\admin\FramogatypeController::class, // framoga turlari
        'ornamenttypes'  => App\Http\Controllers\admin\OrnamenttypeController::class, // naqsh shakllari
        'jambs'          => App\Http\Controllers\admin\JambController::class, // nalichniklar
        'nsjambs'        => App\Http\Controllers\admin\NSJambController::class, // nostandart nalichniklar
        'transoms'       => App\Http\Controllers\admin\TransomController::class, // doborlar
        'framogafigures' => App\Http\Controllers\admin\FramogafigureController::class, // framoga shakllari
        'locktypes'      => App\Http\Controllers\admin\LocktypeController::class, // qulf turlari
        'customers'      => App\Http\Controllers\admin\CustomerController::class, // xaridorlar
        'loops'          => App\Http\Controllers\admin\LoopController::class, // chaspak (петля)
        'workers'        => App\Http\Controllers\admin\WorkerController::class, // xodimlar        
        'glass-types'    => App\Http\Controllers\admin\GlassTypeController::class, // shisha turlari     
        'glass-figures'  => App\Http\Controllers\admin\GlassFigureController::class, // shisha shakllari
        'glasses'        => App\Http\Controllers\admin\GlassController::class, // shishalar     
        'categories'     => App\Http\Controllers\admin\CategoryController::class, // kategoriyalar     
        'products'       => App\Http\Controllers\admin\ProductController::class, // tovarlar
        'warehouses'     => App\Http\Controllers\admin\WarehouseController::class, // skladlar
        'jamb-names'     => App\Http\Controllers\admin\JambnamesController::class, // nalichnik nomlari
        'transom-names'  => App\Http\Controllers\admin\TransomnamesController::class, // dobor nomlari
        'crowns'         => App\Http\Controllers\admin\CrownController::class, // korona
        'cubes'          => App\Http\Controllers\admin\CubeController::class, // kubik
        'boots'          => App\Http\Controllers\admin\BootController::class, // sapog
        'ccbjs'          => App\Http\Controllers\admin\CCBJController::class, // karona+kubik+sapog+nalichnik
    ]);
});

