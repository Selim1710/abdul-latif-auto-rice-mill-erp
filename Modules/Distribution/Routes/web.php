<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::get('distribution', 'DistributionController@index')->name('distribution');
    Route::group(['prefix' => 'distribution', 'as'=>'distribution.'], function () {
        Route::post('datatable-data', 'DistributionController@getDataTableData')->name('datatable.data');
        Route::get('add', 'DistributionController@create')->name('add');
        Route::post('store', 'DistributionController@store')->name('store');
        Route::get('edit/{id}', 'DistributionController@edit')->name('edit');
        Route::post('update', 'DistributionController@update')->name('update');
        Route::get('show/{id}', 'DistributionController@show')->name('show');
        Route::post('delete', 'DistributionController@delete')->name('delete');
        Route::post('change-status', 'DistributionController@changeStatus')->name('change.status');
    });
    Route::get('distribution-category-product/{categoryId}','DistributionController@distributionCategoryProduct');
    Route::get('distribution-product-details/{warehouseId}/{productId}','DistributionController@distributionProductDetails');
});
