<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth','language']], function () {
    Route::get('balance-sheet', 'BalanceSheetController@index')->name('balance.sheet');
    Route::post('balance-sheet/datatable-data', 'BalanceSheetController@balanceSheetData')->name('balance.sheet.data');
    Route::get('due-report', 'DueReportController@index')->name('due.report');
//    Route::post('due-report/data', 'DueReportController@dueReportData')->name('due.report.data');
    Route::post('due-report/data','DueReportController@dueReportData')->name('due.report.data');

    Route::get('employee-ledger', 'EmployeeLedgerController@index')->name('employee.ledger');
    Route::post('employee-ledger/data', 'EmployeeLedgerController@report')->name('employee.ledger.data');

    Route::get('income-statement', 'IncomeStatementReportController@index')->name('income.statement');
    Route::post('income-statement-report', 'IncomeStatementReportController@report')->name('income.statement.report');
});
