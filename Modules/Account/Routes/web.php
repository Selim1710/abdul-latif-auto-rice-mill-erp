<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('opening-balance', 'OpeningBalanceController@index')->name('opening.balance');
    Route::group(['prefix' => 'opening.balance', 'as'=>'opening.balance.'], function () {
        Route::post('datatable-data', 'OpeningBalanceController@get_datatable_data')->name('datatable.data');
        Route::get('create', 'OpeningBalanceController@create')->name('create');
        Route::post('store-or-update', 'OpeningBalanceController@storeOrUpdate')->name('store.or.update');
        Route::get('show/{id}', 'OpeningBalanceController@show')->name('show');
        Route::get('edit/{id}', 'OpeningBalanceController@edit')->name('edit');
        Route::post('change-status', 'OpeningBalanceController@changeStatus')->name('change.status');
        Route::post('delete', 'OpeningBalanceController@delete')->name('delete');
    });
    Route::get('supplier-payment', 'SupplierPaymentController@index')->name('supplier.payment');
    Route::group(['prefix' => 'supplier-payment', 'as'=>'supplier.payment.'], function () {
        Route::post('datatable-data', 'SupplierPaymentController@get_datatable_data')->name('datatable.data');
        Route::get('create', 'SupplierPaymentController@create')->name('create');
        Route::post('store', 'SupplierPaymentController@store')->name('store');
        Route::get('show/{voucher_no}', 'SupplierPaymentController@show')->name('show');
        Route::get('edit/{voucher_no}', 'SupplierPaymentController@edit')->name('edit');
        Route::post('update', 'SupplierPaymentController@update')->name('update');
        Route::post('change-status', 'SupplierPaymentController@changeStatus')->name('change.status');
        Route::post('delete', 'SupplierPaymentController@delete')->name('delete');
    });
    Route::get('customer-receive', 'CustomerReceiveController@index')->name('customer.receive');
    Route::group(['prefix' => 'customer-receive', 'as'=>'customer.receive.'], function () {
        Route::post('datatable-data', 'CustomerReceiveController@get_datatable_data')->name('datatable.data');
        Route::get('create', 'CustomerReceiveController@create')->name('create');
        Route::post('store', 'CustomerReceiveController@store')->name('store');
        Route::get('show/{voucher_no}', 'CustomerReceiveController@show')->name('show');
        Route::get('edit/{voucher_no}', 'CustomerReceiveController@edit')->name('edit');
        Route::post('update', 'CustomerReceiveController@update')->name('update');
        Route::post('change-status', 'CustomerReceiveController@changeStatus')->name('change.status');
        Route::post('delete', 'CustomerReceiveController@delete')->name('delete');
    });
    Route::get('expense', 'ExpenseController@index')->name('expense');
    Route::group(['prefix' => 'expense', 'as'=>'expense.'], function () {
        Route::post('datatable-data', 'ExpenseController@get_datatable_data')->name('datatable.data');
        Route::get('create', 'ExpenseController@create')->name('create');
        Route::post('store', 'ExpenseController@store')->name('store');
        Route::get('show/{voucher_no}', 'ExpenseController@show')->name('show');
        Route::get('edit/{voucher_no}', 'ExpenseController@edit')->name('edit');
        Route::post('update', 'ExpenseController@update')->name('update');
        Route::post('change-status', 'ExpenseController@changeStatus')->name('change.status');
        Route::post('delete', 'ExpenseController@delete')->name('delete');
    });
    Route::get('voucher', 'VoucherController@index')->name('voucher');
    Route::group(['prefix' => 'voucher', 'as'=>'voucher.'], function () {
        Route::post('datatable-data', 'VoucherController@get_datatable_data')->name('datatable.data');
        Route::get('create', 'VoucherController@create')->name('create');
        Route::post('store', 'VoucherController@store')->name('store');
        Route::get('show/{voucher_no}', 'VoucherController@show')->name('show');
        Route::get('edit/{voucher_no}', 'VoucherController@edit')->name('edit');
        Route::post('update', 'VoucherController@update')->name('update');
        Route::post('change-status', 'VoucherController@changeStatus')->name('change.status');
        Route::post('delete', 'VoucherController@delete')->name('delete');
    });
    Route::get('ledger', 'LedgerController@index')->name('ledger');
    Route::post('ledger/data', 'LedgerController@ledgerData')->name('ledger.data');
});
