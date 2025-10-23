<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
       Route::get('sale', 'SaleController@index')->name('sale');
       Route::group(['prefix' => 'sale', 'as'=>'sale.'], function () {
           Route::post('datatable-data', 'SaleController@getDataTableData')->name('datatable.data');
           Route::get('add', 'SaleController@create')->name('add');
           Route::post('store', 'SaleController@store')->name('store');
           Route::get('details/{id}', 'SaleController@show')->name('show');
           Route::get('edit/{id}', 'SaleController@edit')->name('edit');
           Route::get('delivery/{id}', 'SaleController@delivery')->name('delivery');
           Route::post('delivery-store', 'SaleController@deliveryStore')->name('delivery.store');
           Route::get('delivery-invoice/{invoice_no}', 'SaleController@deliveryInvoice')->name('delivery.invoice');
           Route::get('delivery-invoice-gate-pass/{invoice_no}', 'SaleController@deliveryInvoiceGatePass')->name('delivery.invoice.gate.pass');
           Route::get('return/{id}', 'SaleController@return')->name('return');
           Route::post('return-store', 'SaleController@returnStore')->name('return.store');
           Route::get('return-invoice/{invoice_no}', 'SaleController@returnInvoice')->name('return.invoice');
           Route::get('return-invoice-gate-pass/{invoice_no}', 'SaleController@returnInvoiceGatePass')->name('return.invoice.gate.pass');
           Route::post('update', 'SaleController@update')->name('update');
           Route::post('delete', 'SaleController@delete')->name('delete');
           Route::post('change-status', 'SaleController@changeStatus')->name('change.status');
       });
       Route::get('sale-category-product/{categoryId}','SaleController@saleCategoryProduct');
       Route::get('sale-product-details/{warehouseId}/{productId}','SaleController@saleProductDetails');
});
