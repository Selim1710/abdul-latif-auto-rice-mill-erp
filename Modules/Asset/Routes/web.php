<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth','language']], function () {
    //Asset Type Routes
    Route::get('asset-type', 'AssetTypeController@index')->name('asset.type');
    Route::group(['prefix' => 'asset-type', 'as'=>'asset.type.'], function () {
        Route::post('datatable-data', 'AssetTypeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'AssetTypeController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'AssetTypeController@edit')->name('edit');
        Route::post('delete', 'AssetTypeController@delete')->name('delete');
        Route::post('bulk-delete', 'AssetTypeController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'AssetTypeController@change_status')->name('change.status');
    });
    //Asset Routes
    Route::get('asset', 'AssetController@index')->name('asset');
    Route::group(['prefix' => 'asset', 'as'=>'asset.'], function () {
        Route::post('datatable-data', 'AssetController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'AssetController@store_or_update_data')->name('store.or.update');
        Route::post('view', 'AssetController@show')->name('view');
        Route::post('edit', 'AssetController@edit')->name('edit');
        Route::post('delete', 'AssetController@delete')->name('delete');
        Route::post('bulk-delete', 'AssetController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'AssetController@change_status')->name('change.status');
    });
});
