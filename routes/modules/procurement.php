<?php

use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('inventory/receipts', [ProcurementController::class, 'index'])->middleware('permission:purchases.view')->name('receipts.index');
    Route::get('inventory/receipts/create', [ProcurementController::class, 'create'])->middleware('permission:purchases.create')->name('receipts.create');
    Route::post('inventory/receipts', [ProcurementController::class, 'store'])->middleware('permission:purchases.post')->name('receipts.store');
    Route::get('inventory/receipts/{receipt}', [ProcurementController::class, 'show'])->middleware('permission:purchases.view')->name('receipts.show');
    Route::post('inventory/receipts/{receipt}/void', [ProcurementController::class, 'void'])->middleware('permission:purchases.void')->name('receipts.void');
    Route::get('suppliers', [SupplierController::class, 'index'])->middleware('permission:payables.view')->name('suppliers.index');
    Route::post('suppliers', [SupplierController::class, 'store'])->middleware('permission:suppliers.manage')->name('suppliers.store');
    Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->middleware('permission:suppliers.manage')->name('suppliers.update');
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->middleware('permission:payables.view')->name('suppliers.show');
});
