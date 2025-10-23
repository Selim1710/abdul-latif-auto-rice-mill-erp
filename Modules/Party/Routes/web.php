<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','language']], function () {
    Route::get('party', 'PartyController@index')->name('party');
    Route::group(['prefix' => 'party', 'as'=>'party.'], function () {
        Route::post('datatable-data', 'PartyController@getDataTableData')->name('datatable.data');
        Route::post('store-or-update', 'PartyController@storeOrUpdate')->name('store.or.update');
        Route::post('edit', 'PartyController@edit')->name('edit');
        Route::post('view', 'PartyController@show')->name('view');
        Route::post('delete', 'PartyController@delete')->name('delete');
        Route::get('due/{partyId}','PartyController@due')->name('due');
    });
    Route::get('party-ledger', 'PartyLedgerController@index')->name('party.ledger');
    Route::post('party-ledger/data', 'PartyLedgerController@ledgerData')->name('party.ledger.data');
});
