<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('head','HeadController@index')->name('head');
    Route::group(['prefix' => 'head' , 'as' => 'head.'],function(){
        Route::post('datatable-data', 'HeadController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update','HeadController@storeOrUpdate')->name('store.or.update');
        Route::post('update', 'HeadController@update')->name('update');
        Route::post('delete', 'HeadController@delete')->name('delete');
    });
    Route::get('sub-head','SubHeadController@index')->name('sub.head');
    Route::group(['prefix' => 'sub-head' , 'as' => 'sub.head.'],function(){
        Route::post('datatable-data', 'SubHeadController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update','SubHeadController@storeOrUpdate')->name('store.or.update');
        Route::post('update', 'SubHeadController@update')->name('update');
        Route::post('delete', 'SubHeadController@delete')->name('delete');
    });
    Route::get('child-head','ChildHeadController@index')->name('child.head');
    Route::group(['prefix' => 'child-head' , 'as' => 'child.head.'],function(){
        Route::post('datatable-data', 'ChildHeadController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update','ChildHeadController@storeOrUpdate')->name('store.or.update');
        Route::post('update', 'ChildHeadController@update')->name('update');
        Route::post('delete', 'ChildHeadController@delete')->name('delete');
    });
    Route::get('master-head-wise-head','HeadController@masterHeadWiseHead')->name('master.head.wise.head');
    Route::get('head-wise-sub-head','SubHeadController@headWiseSubHead')->name('head.wise.sub.head');
    Route::get('chart-of-head','ChartOfHeadController@index')->name('chart.of.head');
    Route::get('account-list/{paymentMethod}','ChartOfHeadController@accountList')->name('account.list');
});
