<?php

use App\Http\Controllers\CatalogReferenceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductUnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active', 'permission:products.view'])->prefix('catalog')->name('catalog.')->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->middleware('permission:products.create')->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->middleware('permission:products.create')->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->middleware('permission:products.update')->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->middleware('permission:products.update')->name('products.update');
    Route::post('products/{product}/units', [ProductUnitController::class, 'store'])->middleware('permission:product_units.manage')->name('products.units.store');
    Route::put('products/{product}/units/{productUnit}', [ProductUnitController::class, 'update'])->middleware('permission:product_units.manage')->name('products.units.update');
    Route::get('categories', [CatalogReferenceController::class, 'categories'])->name('categories.index');
    Route::post('categories', [CatalogReferenceController::class, 'storeCategory'])->middleware('permission:products.create')->name('categories.store');
    Route::get('units', [CatalogReferenceController::class, 'units'])->name('units.index');
    Route::post('units', [CatalogReferenceController::class, 'storeUnit'])->middleware('permission:product_units.manage')->name('units.store');
});
