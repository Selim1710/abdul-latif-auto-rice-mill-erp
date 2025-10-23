<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
     Route::get('warehouse', 'WarehouseController@index')->name('warehouse');
     Route::group(['prefix' => 'warehouse', 'as'=>'warehouse.'], function () {
         Route::post('datatable-data', 'WarehouseController@get_datatable_data')->name('datatable.data');
         Route::post('store-or-update', 'WarehouseController@store_or_update_data')->name('store.or.update');
         Route::post('edit', 'WarehouseController@edit')->name('edit');
         Route::post('delete', 'WarehouseController@delete')->name('delete');
         Route::post('change-status', 'WarehouseController@change_status')->name('change.status');
     });
});
