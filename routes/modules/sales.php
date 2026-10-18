<?php

use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('pos', [SalesController::class, 'pos'])->middleware('permission:sales.create')->name('pos.index');
    Route::post('pos', [SalesController::class, 'store'])->middleware(['permission:sales.create', 'throttle:60,1'])->name('pos.store');
    Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('sales/{sale}', [SalesController::class, 'show'])->name('sales.show');
    Route::post('sales/{sale}/void', [SalesController::class, 'void'])->middleware('permission:sales.void')->name('sales.void');
});
