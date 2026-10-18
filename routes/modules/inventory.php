<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active', 'permission:stock.view'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::get('movements', [InventoryController::class, 'movements'])->name('movements');
    Route::get('adjustments/create', [InventoryController::class, 'createAdjustment'])->middleware('permission:stock.adjust')->name('adjustments.create');
    Route::post('adjustments', [InventoryController::class, 'storeAdjustment'])->middleware('permission:stock.adjust')->name('adjustments.store');
});
