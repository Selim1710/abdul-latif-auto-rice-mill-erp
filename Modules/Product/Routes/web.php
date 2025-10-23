<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('product', 'ProductController@index')->name('product');
    Route::group(['prefix' => 'product', 'as'=>'product.'], function () {
        Route::get('add', 'ProductController@create')->name('add');
        Route::post('datatable-data', 'ProductController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ProductController@storeOrupdate')->name('store.or.update');
        Route::get('edit/{id}', 'ProductController@edit')->name('edit');
        Route::get('view/{id}', 'ProductController@show');
        Route::post('delete', 'ProductController@delete')->name('delete');
        Route::get('generate-code', 'ProductController@generateProductCode')->name('generate.code');
    });
});
