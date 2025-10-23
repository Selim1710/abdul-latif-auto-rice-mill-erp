<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('labor-head', 'LaborHeadController@index')->name('labor.head');
    Route::group(['prefix' => 'labor-head', 'as'=>'labor.head.'], function () {
        Route::post('datatable-data', 'LaborHeadController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'LaborHeadController@storeOrUpdateData')->name('store.or.update');
        Route::post('edit', 'LaborHeadController@edit')->name('edit');
        Route::post('view', 'LaborHeadController@show')->name('view');
        Route::post('delete', 'LaborHeadController@delete')->name('delete');
        Route::post('change-status', 'LaborHeadController@changeStatus')->name('change.status');
    });
    Route::get('labor-bill', 'LaborBillController@index')->name('labor.bill');
    Route::group(['prefix' => 'labor-bill', 'as'=>'labor.bill.'], function () {
        Route::post('datatable-data', 'LaborBillController@getDataTableData')->name('datatable.data');
        Route::get('create', 'LaborBillController@create')->name('create');
        Route::post('store', 'LaborBillController@store')->name('store');
        Route::get('show/{invoice_no}', 'LaborBillController@show')->name('show');
        Route::get('edit/{invoice_no}', 'LaborBillController@edit')->name('edit');
        Route::post('update', 'LaborBillController@update')->name('update');
        Route::post('delete', 'LaborBillController@delete')->name('delete');
        Route::post('change-status', 'LaborBillController@changeStatus')->name('change.status');
    });
    Route::get('labor-bill-rate', 'LaborBillRateController@index')->name('labor.bill.rate');
    Route::group(['prefix' => 'labor-bill-rate', 'as'=>'labor.bill.rate.'], function () {
        Route::post('datatable-data', 'LaborBillRateController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'LaborBillRateController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'LaborBillRateController@edit')->name('edit');
        Route::post('delete', 'LaborBillRateController@delete')->name('delete');
    });
});
