<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('stock-transfer', 'StockTransferController@index')->name('stock.transfer');
    Route::group(['prefix' => 'stock-transfer', 'as'=>'stock.transfer.'], function () {
        Route::post('index', 'StockTransferController@index')->name('index');
        Route::post('datatable-data', 'StockTransferController@getDataTableData')->name('datatable.data');
        Route::get('add', 'StockTransferController@create')->name('add');
        Route::post('store', 'StockTransferController@store')->name('store');
        Route::get('show/{id}', 'StockTransferController@show')->name('show');
        Route::get('edit/{id}', 'StockTransferController@edit')->name('edit');
        Route::post('update', 'StockTransferController@update')->name('update');
        Route::post('change-status', 'StockTransferController@changeStatus')->name('change.status');
        Route::post('delete', 'StockTransferController@delete')->name('delete');
        Route::get('warehouse-product/{warehouse_id}', 'StockTransferController@warehouseProduct')->name('warehouse.product');
    });
    Route::get('product-alert','ProductAlertController@index')->name('product.alert');
    Route::group(['prefix' => 'product-alert' , 'as' => 'product.alert.'], function(){
        Route::post('datatable-data', 'ProductAlertController@getDataTableData')->name('datatable.data');
    });
    Route::get('product-stock', 'ProductStockController@index')->name('product.stock');
    Route::group(['prefix' => 'product-stock', 'as'=>'product.stock.'], function () {
        Route::post('datatable-data', 'ProductStockController@get_product_stock_data')->name('datatable.data');
    });
    Route::get('product-ledger', 'ProductLedgerController@index')->name('product.ledger');
    Route::post('product-ledger/data', 'ProductLedgerController@productLedgerData')->name('product.ledger.data');
});
