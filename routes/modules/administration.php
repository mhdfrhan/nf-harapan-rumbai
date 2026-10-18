<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\StoreSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('admin/users', [AdministrationController::class, 'users'])->middleware('permission:users.view')->name('admin.users');
    Route::post('admin/users', [AdministrationController::class, 'storeUser'])->middleware('permission:users.create')->name('admin.users.store');
    Route::put('admin/users/{user}', [AdministrationController::class, 'updateUser'])->middleware('permission:users.update')->name('admin.users.update');
    Route::put('admin/users/{user}/password', [AdministrationController::class, 'resetPassword'])->middleware('permission:users.update')->name('admin.users.password');
    Route::get('admin/audit', [AdministrationController::class, 'audit'])->middleware('permission:audit.view')->name('admin.audit');
    Route::get('settings/store', [StoreSettingsController::class, 'edit'])->middleware('permission:settings.view')->name('settings.store');
    Route::put('settings/store', [StoreSettingsController::class, 'update'])->middleware('permission:settings.manage')->name('settings.store.update');
});
