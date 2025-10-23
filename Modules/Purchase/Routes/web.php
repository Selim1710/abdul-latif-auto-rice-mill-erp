<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\PurchaseController;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('purchase', [PurchaseController::class,'index'])->name('purchase');
    Route::group(['prefix' => 'purchase', 'as'=>'purchase.'], function () {
        Route::get('add', [PurchaseController::class,'create'])->name('add');
        Route::post('datatable-data', [PurchaseController::class,'getDataTableData'])->name('datatable.data');
        Route::post('store', [PurchaseController::class,'store'])->name('store');
        Route::get('details/{id}', [PurchaseController::class,'purchaseDetails'])->name('details');
        Route::get('edit/{id}', [PurchaseController::class,'edit'])->name('edit');
        Route::post('update', [PurchaseController::class,'update'])->name('update');
        Route::get('receive/{id}', [PurchaseController::class,'receive'])->name('receive');
        Route::post('receive-store', [PurchaseController::class,'receiveStore'])->name('receive.store');
        Route::get('receive-invoice/{invoiceNo}', [PurchaseController::class,'receiveDetails'])->name('receive.details');
        Route::get('receive-gate-pass/{invoiceNo}', [PurchaseController::class,'receiveGatePass'])->name('receive.gate.pass');
        Route::get('return/{id}', [PurchaseController::class,'return'])->name('return');
        Route::post('return-store', [PurchaseController::class,'returnStore'])->name('return.store');
        Route::get('return-invoice/{invoiceNo}',[PurchaseController::class,'returnDetails'])->name('return.details');
        Route::get('return-gate-pass/{invoiceNo}', [PurchaseController::class,'returnGatePass'])->name('return.gate.pass');
        Route::post('change-status', [PurchaseController::class,'changeStatus'])->name('change.status');
        Route::post('delete', [PurchaseController::class,'delete'])->name('delete');
    });
    Route::get('purchase-category-product/{categoryId}',[PurchaseController::class,'purchaseCategoryProduct']);
    Route::get('purchase-product-details/{productId}',[PurchaseController::class,'purchaseProductDetails']);
});
