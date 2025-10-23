<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('transport', 'TransportController@index')->name('transport');
    Route::group(['prefix' => 'transport', 'as' => 'transport.'], function () {
        Route::get('add', 'TransportController@create')->name('add');
        Route::post('datatable-data', 'TransportController@get_datatable_data')->name('datatable.data');
        Route::post('store', 'TransportController@store')->name('store');
        Route::post('update', 'TransportController@update')->name('update');
        Route::get('edit/{id}', 'TransportController@edit')->name('edit');
        Route::get('view/{id}', 'TransportController@show')->name('view');
        Route::post('change-status', 'TransportController@changeStatus')->name('change.status');
        Route::post('delete', 'TransportController@delete')->name('delete');
    });
     Route::get('truck', 'TruckController@index')->name('truck');
     Route::group(['prefix' => 'truck', 'as'=>'truck.'], function () {
         Route::post('datatable-data', 'TruckController@get_datatable_data')->name('datatable.data');
         Route::post('store-or-update', 'TruckController@store_or_update_data')->name('store.or.update');
         Route::post('edit', 'TruckController@edit')->name('edit');
         Route::post('delete', 'TruckController@delete')->name('delete');
         Route::post('change-status', 'TruckController@change_status')->name('change.status');
     });
});
