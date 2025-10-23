<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth','language']], function () {
    Route::get('expense-item', 'ExpenseItemController@index')->name('expense.item');
    Route::group(['prefix' => 'expense-item', 'as'=>'expense.item.'], function () {
        Route::post('datatable-data', 'ExpenseItemController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ExpenseItemController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ExpenseItemController@edit')->name('edit');
        Route::post('delete', 'ExpenseItemController@delete')->name('delete');
    });
    Route::get('expense-ledger', 'ExpenseLedgerController@index')->name('expense.ledger');
    Route::post('expense-ledger/data', 'ExpenseLedgerController@ExpenseledgerData')->name('expense.ledger.data');
});
