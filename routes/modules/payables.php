<?php

use App\Http\Controllers\PayableController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active', 'permission:payables.view'])->prefix('payables')->name('payables.')->group(function () {
    Route::get('/', [PayableController::class, 'index'])->name('index');
    Route::get('{payable}', [PayableController::class, 'show'])->name('show');
    Route::get('{payable}/payments/create', [PayableController::class, 'createPayment'])->middleware('permission:payable_payments.post')->name('payments.create');
    Route::post('{payable}/payments', [PayableController::class, 'storePayment'])->middleware('permission:payable_payments.post')->name('payments.store');
    Route::post('{payable}/payments/{payment}/void', [PayableController::class, 'voidPayment'])->middleware('permission:payable_payments.void')->name('payments.void');
});
