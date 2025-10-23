<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('loan-categories','LoanCategoriesController@index')->name('loan.categories');
    Route::group(['prefix' => 'loan-categories','as' => 'loan.categories.'],function(){
       Route::post('datatable-data','LoanCategoriesController@getDataTableData')->name('datatable.data');
       Route::get('add', 'LoanCategoriesController@create')->name('add');
       Route::post('store-or-update', 'LoanCategoriesController@storeOrUpdate')->name('store.or.update');
       Route::post('edit', 'LoanCategoriesController@edit')->name('edit');
       Route::post('delete', 'LoanCategoriesController@delete')->name('delete');
    });
    Route::get('loan','LoanController@index')->name('loan');
    Route::group(['prefix' => 'loan' , 'as' => 'loan.'],function(){
       Route::post('datatable-data','LoanController@getDataTableData')->name('datatable.data');
       Route::get('add','LoanController@create')->name('add');
       Route::post('store','LoanController@store')->name('store');
       Route::get('details/{id}', 'LoanController@show')->name('show');
       Route::get('edit/{id}','LoanController@edit')->name('edit');
       Route::post('update','LoanController@update')->name('update');
       Route::post('change-status','LoanController@changeStatus')->name('change.status');
       Route::post('delete','LoanController@delete')->name('delete');
    });
    Route::get('loan-installment','LoanInstallmentController@index')->name('loan.installment');
    Route::group(['prefix' => 'loan-installment' , 'as' => 'loan.installment.'],function(){
       Route::post('datatable-data','LoanInstallmentController@getDataTableData')->name('datatable.data');
       Route::get('add','LoanInstallmentController@create')->name('add');
       Route::post('store','LoanInstallmentController@store')->name('store');
       Route::get('details/{id}', 'LoanInstallmentController@show')->name('show');
       Route::get('edit/{id}','LoanInstallmentController@edit')->name('edit');
       Route::post('update','LoanInstallmentController@update')->name('update');
       Route::post('change-status','LoanInstallmentController@changeStatus')->name('change.status');
       Route::post('delete','LoanInstallmentController@delete')->name('delete');
    });
    Route::get('loan-ledger','LoanLedgerController@index')->name('loan.ledger');
    Route::group(['prefix' => 'loan-ledger' , 'as' => 'loan.ledger.'],function(){
       Route::post('datatable-data','LoanLedgerController@getDataTableData')->name('datatable.data');
    });
});

