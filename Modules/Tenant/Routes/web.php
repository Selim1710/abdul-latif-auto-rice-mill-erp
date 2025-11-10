<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'language']], function () {
    Route::get('tenant', 'TenantController@index')->name('tenant');
    Route::group(['prefix' => 'tenant', 'as' => 'tenant.'], function () {
        Route::post('datatable-data', 'TenantController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'TenantController@storeOrUpdateData')->name('store.or.update');
        Route::post('edit', 'TenantController@edit')->name('edit');
        Route::post('view', 'TenantController@show')->name('view');
        Route::post('delete', 'TenantController@delete')->name('delete');
        Route::post('change-status', 'TenantController@changeStatus')->name('change.status');
    });
    Route::get('tenant-receive-product', 'TenantReceiveProductController@index')->name('tenant.receive.product');
    Route::get('tenant-batch-no', 'TenantReceiveProductController@batchNo')->name('tenant-batch-no');
    Route::group(['prefix' => 'tenant-receive-product', 'as' => 'tenant.receive.product.'], function () {
        Route::get('add', 'TenantReceiveProductController@create')->name('add');
        Route::post('datatable-data', 'TenantReceiveProductController@getDataTableData')->name('datatable.data');
        Route::post('store', 'TenantReceiveProductController@store')->name('store');
        Route::get('edit/{id}', 'TenantReceiveProductController@edit')->name('edit');
        Route::post('update', 'TenantReceiveProductController@update')->name('update');
        Route::get('view/{id}', 'TenantReceiveProductController@show')->name('view');
        Route::post('delete', 'TenantReceiveProductController@delete')->name('delete');
        Route::post('change-status', 'TenantReceiveProductController@changeStatus')->name('change.status');
        Route::get('category', 'TenantReceiveProductController@categoryProduct')->name('category');
        Route::get('details', 'TenantReceiveProductController@productDetails')->name('details');
    });
    Route::get('tenant-return-product', 'TenantReturnProductController@index')->name('tenant.return.product');
    Route::get('tenant-return-product-data', 'TenantReturnProductController@returnData')->name('tenant.return.product.data');
    Route::group(['prefix' => 'tenant-return-product', 'as' => 'tenant.return.product.'], function () {
        Route::get('add', 'TenantReturnProductController@create')->name('add');
        Route::post('datatable-data', 'TenantReturnProductController@getDataTableData')->name('datatable.data');
        Route::post('store', 'TenantReturnProductController@store')->name('store');
        Route::get('edit/{id}', 'TenantReturnProductController@edit')->name('edit');
        Route::post('update', 'TenantReturnProductController@update')->name('update');
        Route::get('view/{id}', 'TenantReturnProductController@show')->name('view');
        Route::post('delete', 'TenantReturnProductController@delete')->name('delete');
        Route::post('change-status', 'TenantReturnProductController@changeStatus')->name('change.status');
        Route::get('category', 'TenantReturnProductController@categoryProduct')->name('category');
        Route::get('details', 'TenantReturnProductController@productDetails')->name('details');
    });
    Route::get('tenant-delivery-product', 'TenantDeliveryProductController@index')->name('tenant.delivery.product');

    Route::get('tenant-delivery-product-data', 'TenantDeliveryProductController@deliveryData')->name('tenant.delivery.product.data');

    Route::group(['prefix' => 'tenant.delivery.product', 'as' => 'tenant.delivery.product.'], function () {
        Route::get('add', 'TenantDeliveryProductController@create')->name('add');
        Route::post('datatable-data', 'TenantDeliveryProductController@getDataTableData')->name('datatable.data');
        Route::post('store', 'TenantDeliveryProductController@store')->name('store');
        Route::get('edit/{id}', 'TenantDeliveryProductController@edit')->name('edit');
        Route::post('update', 'TenantDeliveryProductController@update')->name('update');
        Route::get('view/{id}', 'TenantDeliveryProductController@show')->name('view');
        Route::post('delete', 'TenantDeliveryProductController@delete')->name('delete');
        Route::post('change-status', 'TenantDeliveryProductController@changeStatus')->name('change.status');
        Route::get('category', 'TenantDeliveryProductController@categoryProduct')->name('category');
        Route::get('details', 'TenantDeliveryProductController@productDetails')->name('details');
    });
    Route::get('tenant-stock', 'TenantWarehouseProductController@index')->name('tenant.stock');
    Route::group(['prefix' => 'tenant-stock', 'as' => 'tenant.stock.'], function () {
        Route::post('datatable-data', 'TenantWarehouseProductController@getDataTableData')->name('datatable.data');
    });
});
