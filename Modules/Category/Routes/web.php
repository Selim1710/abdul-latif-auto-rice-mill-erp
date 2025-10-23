<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('category', 'CategoryController@index')->name('category');
    Route::group(['prefix' => 'category', 'as'=>'category.'], function () {
        Route::post('datatable-data', 'CategoryController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'CategoryController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'CategoryController@edit')->name('edit');
        Route::post('delete', 'CategoryController@delete')->name('delete');
        Route::post('change-status', 'CategoryController@changeStatus')->name('change.status');
    });
});
