<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReadinessController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');
Route::get('health/ready', ReadinessController::class)->middleware('throttle:30,1')->name('health.ready');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::redirect('profile', 'settings/profile');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
});

require __DIR__.'/settings.php';
require __DIR__.'/modules/administration.php';
require __DIR__.'/modules/catalog.php';
require __DIR__.'/modules/inventory.php';
require __DIR__.'/modules/payables.php';
require __DIR__.'/modules/procurement.php';
require __DIR__.'/modules/reports.php';
require __DIR__.'/modules/sales.php';
