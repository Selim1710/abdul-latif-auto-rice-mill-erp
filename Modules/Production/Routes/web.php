<?php

use Illuminate\Support\Facades\Route;
use Modules\Production\Http\Controllers\ProductionController;
use Modules\Production\Http\Controllers\ProductionSaleController;
use Modules\Production\Http\Controllers\ProductionProductController;

Route::group(['middleware' => ['auth', 'language']], function () {
    Route::get('production',  [ProductionController::class, 'index'])->name('production');
    Route::group(['prefix' => 'production', 'as' => 'production.'], function () {
        Route::post('datatable-data', [ProductionController::class, 'getDataTableData'])->name('datatable.data');
        Route::get('add', [ProductionController::class, 'create'])->name('add');
        Route::post('store', [ProductionController::class, 'store'])->name('store');
        Route::get('view/{id}', [ProductionController::class, 'show'])->name('show');
        Route::get('edit/{id}', [ProductionController::class, 'edit'])->name('edit');
        Route::post('update', [ProductionController::class, 'update'])->name('update');
        Route::post('change-status', [ProductionController::class, 'changeStatus'])->name('change.status');
        Route::get('product/{id}', [ProductionController::class, 'production'])->name('product');
        Route::post('complete', [ProductionController::class, 'complete'])->name('complete');
        Route::get('report-details/{id}', [ProductionController::class, 'reportDetails'])->name('report.details');
        Route::get('summary/{id}', [ProductionController::class, 'summary'])->name('summary');
        Route::post('delete', [ProductionController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix' => 'production-sale', 'as' => 'production.sale.'], function () {
        Route::get('add/{id}', [ProductionSaleController::class, 'create'])->name('add');
        Route::post('store', [ProductionSaleController::class, 'store'])->name('store');
        Route::get('details/{id}', [ProductionSaleController::class, 'show'])->name('show');
        Route::get('gate-pass/{id}', [ProductionSaleController::class, 'gatePass'])->name('gate.pass');
        Route::get('packing/{id}', [ProductionSaleController::class, 'packing'])->name('packing');
    });
    Route::group(['prefix' => 'production-product', 'as' => 'production.product.'], function () {
        Route::get('add/{id}', [ProductionProductController::class, 'create'])->name('add');
        Route::post('store', [ProductionProductController::class, 'store'])->name('store');
        Route::get('details/{invoice_no}', [ProductionProductController::class, 'show'])->name('show');
        Route::get('gate-pass/{invoice_no}', [ProductionProductController::class, 'gatePass'])->name('gate.pass');
        Route::get('packing/{invoice_no}', [ProductionProductController::class, 'packing'])->name('packing');
    });
    Route::get('category-product/{categoryId}',  [ProductionController::class, 'categoryProduct'])->name('category.product');
    Route::get('product-details/{productId}',  [ProductionController::class, 'productDetails'])->name('product.details');
    Route::get('warehouse-product/{warehouseId}/{productId}',  [ProductionController::class, 'warehouseProduct'])->name('warehouse.product');
});
