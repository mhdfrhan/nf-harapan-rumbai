<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('sales', [ReportController::class, 'sales'])->middleware('permission:reports.sales')->name('sales');
    Route::get('inventory', [ReportController::class, 'inventory'])->middleware('permission:reports.stock')->name('inventory');
    Route::get('purchases', [ReportController::class, 'purchases'])->middleware('permission:reports.stock')->name('purchases');
    Route::get('payables', [ReportController::class, 'payables'])->middleware('permission:reports.payables')->name('payables');
});
