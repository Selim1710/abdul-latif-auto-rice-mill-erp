<?php

use Illuminate\Support\Facades\Route;
use Modules\TenantProduction\Http\Controllers\TenantProductionController;
use Modules\TenantProduction\Http\Controllers\TenantProductionDeliveryController;
use Modules\TenantProduction\Http\Controllers\TenantProductionProductController;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('tenant-production',  [TenantProductionController::class,'index'])->name('tenant.production');
    Route::group(['prefix' => 'tenant-production', 'as'=>'tenant.production.'], function () {
        Route::post('datatable-data', [TenantProductionController::class,'getDataTableData'])->name('datatable.data');
        Route::get('add', [TenantProductionController::class,'create'])->name('add');
        Route::post('store', [TenantProductionController::class,'store'])->name('store');
        Route::get('view/{id}', [TenantProductionController::class,'show'])->name('show');
        Route::get('edit/{id}', [TenantProductionController::class,'edit'])->name('edit');
        Route::post('update', [TenantProductionController::class,'update'])->name('update');
        Route::post('change-status', [TenantProductionController::class,'changeStatus'])->name('change.status');
        Route::get('product/{id}', [TenantProductionController::class,'production'])->name('product');
        Route::post('complete', [TenantProductionController::class,'complete'])->name('complete');
        Route::get('report-details/{id}',[TenantProductionController::class,'reportDetails'])->name('report.details');
        Route::get('summary/{id}', [TenantProductionController::class,'summary'])->name('summary');
        Route::post('delete', [TenantProductionController::class,'delete'])->name('delete');
    });
    Route::group(['prefix' => 'tenant-production-delivery', 'as'=>'tenant.production.delivery.'], function () {
        Route::get('add/{id}', [TenantProductionDeliveryController::class,'create'])->name('add');
        Route::post('store', [TenantProductionDeliveryController::class,'store'])->name('store');
        Route::get('details/{id}', [TenantProductionDeliveryController::class,'show'])->name('show');
        Route::get('packing/{id}', [TenantProductionDeliveryController::class,'packing'])->name('packing');
    });
    Route::group(['prefix' => 'tenant-production-product', 'as'=>'tenant.production.product.'], function () {
        Route::get('add/{id}', [TenantProductionProductController::class,'create'])->name('add');
        Route::post('store', [TenantProductionProductController::class,'store'])->name('store');
        Route::get('details/{invoice_no}', [TenantProductionProductController::class,'show'])->name('show');
        Route::get('packing/{invoice_no}', [TenantProductionProductController::class,'packing'])->name('packing');
    });
    Route::get('tenant-category-product/{categoryId}',  [TenantProductionController::class,'categoryProduct'])->name('tenant.category.product');
    Route::get('tenant-product-details/{productId}',  [TenantProductionController::class,'productDetails'])->name('tenant.product.details');
    Route::get('tenant-warehouse-product/{tenantId}/{warehouseId}/{productId}',  [TenantProductionController::class,'warehouseProduct'])->name('tenant.warehouse.product');
    Route::get('tenant-merge-category-product/{categoryId}',  [TenantProductionController::class,'mergeCategoryProduct'])->name('merge.category.product');
    Route::get('tenant-merge-product-details/{productId}',  [TenantProductionController::class,'mergeProductDetails'])->name('merge.product.details');
    Route::get('tenant-merge-warehouse-product/{warehouseId}/{productId}',  [TenantProductionController::class,'mergeWarehouseProduct'])->name('merge.warehouse.product');
});
