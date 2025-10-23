<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth','language']], function () {
    Route::get('mill', 'MillController@index')->name('mill');
    Route::group(['prefix' => 'mill', 'as'=>'mill.'], function () {
        Route::get('create', 'MillController@create')->name('create');
        Route::post('datatable-data', 'MillController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MillController@storeOrUpdateData')->name('store.or.update');
        Route::post('edit', 'MillController@edit')->name('edit');
        Route::post('delete', 'MillController@delete')->name('delete');
    });
    Route::get('mill-income', 'MillIncomeController@index')->name('mill.income');
    Route::group(['prefix' => 'mill-income', 'as'=>'mill.income.'], function () {
        Route::get('create', 'MillIncomeController@create')->name('create');
        Route::post('datatable-data', 'MillIncomeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MillIncomeController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'MillIncomeController@edit')->name('edit');
        Route::post('delete', 'MillIncomeController@delete')->name('delete');
    });
    Route::get('mill-expense', 'MillExpenseController@index')->name('mill.expense');
    Route::group(['prefix' => 'mill-expense', 'as'=>'mill.expense.'], function () {
        Route::post('datatable-data', 'MillExpenseController@get_datatable_data')->name('datatable.data');
    });
    Route::get('mill-ledger', 'MillLedgerController@index')->name('mill.ledger');
    Route::group(['prefix' => 'mill-ledger', 'as'=>'mill.ledger.'], function () {
        Route::post('datatable-data', 'MillLedgerController@get_datatable_data')->name('datatable.data');
    });
});
