<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    //Employee Routes
    Route::get('manage-employee', 'EmployeeController@index')->name('employee');
    Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
        Route::get('add-employee', 'EmployeeController@add')->name('add');
        Route::post('datatable-data', 'EmployeeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'EmployeeController@store_or_update_data')->name('store.or.update');
        Route::get('edit/{id}', 'EmployeeController@edit')->name('edit');
        Route::post('update', 'EmployeeController@update_data')->name('update');
        Route::post('delete', 'EmployeeController@delete')->name('delete');
        Route::post('change-status', 'EmployeeController@change_status')->name('change.status');
    });

    //Labour Routes
    Route::get('manage-labour', 'LabourController@index')->name('labour');
    Route::group(['prefix' => 'labour', 'as' => 'labour.'], function () {
        Route::get('add-labour', 'LabourController@add')->name('add');
        Route::post('datatable-data', 'LabourController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LabourController@store_or_update_data')->name('store.or.update');
        Route::get('edit/{id}', 'LabourController@edit')->name('edit');
        Route::post('update', 'LabourController@update_data')->name('update');
        Route::post('delete', 'LabourController@delete')->name('delete');
        Route::post('change-status', 'LabourController@change_status')->name('change.status');
    });

    //salary Setup Routes
    Route::get('manage-salary', 'SalaryController@index')->name('salary');
    Route::group(['prefix' => 'salary', 'as' => 'salary.'], function () {
        Route::post('/add-employee-salary/go', 'SalaryController@go');
        Route::get('/manage-salary/{id}', 'SalaryController@create');
        Route::get('add-employee', 'SalaryController@add')->name('add');
        Route::post('datatable-data', 'SalaryController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'SalaryController@store_or_update_data')->name('store.or.update');
        Route::post('update', 'SalaryController@update_data')->name('update');
        Route::get('edit/{id}', 'SalaryController@edit')->name('edit');
        Route::post('delete', 'SalaryController@delete')->name('delete');
        Route::post('change-status', 'SalaryController@change_status')->name('change.status');
    });

    //Monthly Employee salary Payment Setup Routes
    Route::get('manage-salary-payment', 'SalaryPaymentController@index')->name('salaryPayment');
    Route::group(['prefix' => 'salaryPayment', 'as' => 'salaryPayment.'], function () {
        Route::get('/employee-salary/go', 'SalaryPaymentController@create')->name('salary.create');
        Route::get('/employee-allowance/{id}/{month}', 'SalaryPaymentController@allowanceView')->name('salary.allowance');
        Route::get('/employee-deduction/{id}/{month}', 'SalaryPaymentController@deductionView')->name('salary.deduction');
        Route::get('/salary-payslip/{id}', 'SalaryPaymentController@payslipView')->name('salary.payslip');
        Route::get('add-employee', 'SalaryPaymentController@add')->name('add');
        Route::post('datatable-data', 'SalaryPaymentController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'SalaryPaymentController@store_or_update_data')->name('store.or.update');
        Route::post('update', 'SalaryPaymentController@update_data')->name('update');
        Route::get('edit/{id}', 'SalaryPaymentController@edit')->name('edit');
        Route::post('delete', 'SalaryPaymentController@delete')->name('delete');
        Route::post('change-status', 'SalaryPaymentController@change_status')->name('change.status');
    });

    //Monthly Labour salary Payment Setup Routes
    Route::get('manage-labour-salary-payment', 'LabourSalaryPaymentController@index')->name('labourSalaryPayment');
    Route::group(['prefix' => 'labourSalaryPayment', 'as' => 'labourSalaryPayment.'], function () {
        Route::get('/labour-salary/go', 'LabourSalaryPaymentController@create')->name('salary.create');
        Route::get('/labour-allowance/{id}/{month}', 'LabourSalaryPaymentController@allowanceView')->name('salary.allowance');
        Route::get('/labour-deduction/{id}/{month}', 'LabourSalaryPaymentController@deductionView')->name('salary.deduction');
        Route::get('/salary-payslip/{id}', 'LabourSalaryPaymentController@payslipView')->name('salary.payslip');
        Route::get('add-labour', 'LabourSalaryPaymentController@add')->name('add');
        Route::post('datatable-data', 'LabourSalaryPaymentController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LabourSalaryPaymentController@store_or_update_data')->name('store.or.update');
        Route::post('update', 'LabourSalaryPaymentController@update_data')->name('update');
        Route::get('edit/{id}', 'LabourSalaryPaymentController@edit')->name('edit');
        Route::post('delete', 'LabourSalaryPaymentController@delete')->name('delete');
        Route::post('change-status', 'LabourSalaryPaymentController@change_status')->name('change.status');
    });

    //salary PaySlip Routes
    Route::get('generate-salary-sheet-and-payslip', 'PaySlipController@index')->name('payslip');
    Route::group(['prefix' => 'payslip', 'as' => 'payslip.'], function () {
        Route::post('datatable-data', 'PaySlipController@get_datatable_data')->name('datatable.data');
        Route::get('view-payslip', 'PaySlipController@payslip')->name('view.payslip');
    });

    //Salary Statement Routes
    Route::get('salary-statement', 'SalaryStatementController@index')->name('statement');
    Route::group(['prefix' => 'statement', 'as' => 'statement.'], function () {
        Route::post('datatable-data', 'SalaryStatementController@get_datatable_data')->name('datatable.data');
        Route::get('salary-statement', 'SalaryStatementController@salaryStatement')->name('salary.statement');
    });

    //Employee Salary Advance Routes
    Route::get('manage-salary-advance', 'SalaryAdvanceController@index')->name('salaryAdvance');
    Route::group(['prefix' => 'salaryAdvance', 'as' => 'salaryAdvance.'], function () {
        Route::post('datatable-data', 'SalaryAdvanceController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'SalaryAdvanceController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'SalaryAdvanceController@edit')->name('edit');
    });

    //Labour Salary Advance Routes
    Route::get('labour-salary-advance', 'LabourAdvanceController@index')->name('labourSalaryAdvance');
    Route::group(['prefix' => 'labourSalaryAdvance', 'as' => 'labourSalaryAdvance.'], function () {
        Route::post('datatable-data', 'LabourAdvanceController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LabourAdvanceController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'LabourAdvanceController@edit')->name('edit');
    });

    //Provident Fund Routes
    Route::get('provident-funds', 'ProvidentFundController@index')->name('providentFund');
    Route::group(['prefix' => 'providentFund', 'as' => 'providentFund.'], function () {
        Route::post('datatable-data', 'ProvidentFundController@get_datatable_data')->name('datatable.data');
        Route::get('/provident-fund/{id}', 'ProvidentFundController@view')->name('view.providentFund');
    });

    //salary Increment Setup Routes
    Route::get('manage-increment', 'IncrementController@index')->name('increment');
    Route::group(['prefix' => 'increment', 'as' => 'increment.'], function () {
        Route::get('add-increment', 'IncrementController@add')->name('add');
        Route::post('datatable-data', 'IncrementController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'IncrementController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'IncrementController@edit')->name('edit');
        Route::post('delete', 'IncrementController@delete')->name('delete');
    });

    //Employee Deduction Setup Routes
    Route::get('manage-deductions', 'DeductionController@index')->name('deduction');
    Route::group(['prefix' => 'deduction', 'as' => 'deduction.'], function () {
        Route::post('datatable-data', 'DeductionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'DeductionController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'DeductionController@edit')->name('edit');
    });

    //Labour Deduction Setup Routes
    // Route::get('manage-labour-deductions', 'LabourDeductionController@index')->name('deduction');
    Route::group(['prefix' => 'labourDeduction', 'as' => 'labourDeduction.'], function () {
        Route::post('datatable-data', 'LabourDeductionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LabourDeductionController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'LabourDeductionController@edit')->name('edit');
    });

    //Employee Allowances Setup Routes
    Route::get('manage-allowances', 'AllowancesController@index')->name('allowances');
    Route::group(['prefix' => 'allowances', 'as' => 'allowances.'], function () {
        Route::post('datatable-data', 'AllowancesController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'AllowancesController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'AllowancesController@edit')->name('edit');
    });

    //Labour Allowances Setup Routes
    // Route::get('manage-labour-allowance', 'LabourAllowancesController@index')->name('allowances');
    Route::group(['prefix' => 'labourAllowances', 'as' => 'labourAllowances.'], function () {
        Route::post('datatable-data', 'LabourAllowancesController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LabourAllowancesController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'LabourAllowancesController@edit')->name('edit');
    });

    //Employee Bonus Setup Routes
    Route::get('manage-bonuses', 'BonusControllerController@index')->name('bonus');
    Route::group(['prefix' => 'bonus', 'as' => 'bonus.'], function () {
        Route::post('datatable-data', 'BonusControllerController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'BonusControllerController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'BonusControllerController@edit')->name('edit');
        Route::post('delete', 'BonusControllerController@delete')->name('delete');
        Route::post('change-status', 'BonusControllerController@change_status')->name('change.status');
    });

    //Labour Bonus Setup Routes
    // Route::get('manage-labour-bonus', 'LabourBonusController@index')->name('bonus');
    Route::group(['prefix' => 'bonus', 'as' => 'bonus.'], function () {
        Route::post('datatable-data', 'LabourBonusController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LabourBonusController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'LabourBonusController@edit')->name('edit');
        Route::post('delete', 'LabourBonusController@delete')->name('delete');
        Route::post('change-status', 'LabourBonusController@change_status')->name('change.status');
    });

    //Employee Leave Setup Routes
    Route::get('employee-leave-application', 'EmployeeLeaveAppController@index')->name('empLeave');
    Route::group(['prefix' => 'empLeave', 'as' => 'empLeave.'], function () {
        Route::post('datatable-data', 'EmployeeLeaveAppController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'EmployeeLeaveAppController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'EmployeeLeaveAppController@edit')->name('edit');
        Route::post('delete', 'EmployeeLeaveAppController@delete')->name('delete');
        Route::post('change-status', 'EmployeeLeaveAppController@change_status')->name('change.status');
    });

    //Labour Leave Setup Routes
    Route::get('labour-leave-application', 'LabourLeaveAppController@index')->name('labourLeave');
    Route::group(['prefix' => 'labourLeave', 'as' => 'labourLeave.'], function () {
        Route::post('datatable-data', 'LabourLeaveAppController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LabourLeaveAppController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'LabourLeaveAppController@edit')->name('edit');
        Route::post('delete', 'LabourLeaveAppController@delete')->name('delete');
        Route::post('change-status', 'LabourLeaveAppController@change_status')->name('change.status');
    });


    //Leave Category Setup Routes
    Route::get('manage-leave-category', 'LeaveCatController@index')->name('leave');
    Route::group(['prefix' => 'leave', 'as' => 'leave.'], function () {
        Route::post('/add-employee-salary/go', 'LeaveCatController@go');
        Route::get('/manage-salary/{id}', 'LeaveCatController@create');
        Route::get('add-employee', 'LeaveCatController@add')->name('add');
        Route::post('datatable-data', 'LeaveCatController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LeaveCatController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'LeaveCatController@edit')->name('edit');
        Route::post('delete', 'LeaveCatController@delete')->name('delete');
        Route::post('change-status', 'LeaveCatController@change_status')->name('change.status');
    });

    //Working Time Setup Routes
    Route::get('attendance-setting', 'AttendanceSettingController@index')->name('attendanceSetting');
    Route::group(['prefix' => 'attendanceSetting', 'as' => 'attendanceSetting.'], function () {
        Route::post('datatable-data', 'AttendanceSettingController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'AttendanceSettingController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'AttendanceSettingController@edit')->name('edit');
    });


    //Working Day Setup Routes
    Route::get('manage-working-days', 'WorkingDayController@index')->name('workingDays');
    Route::group(['prefix' => 'workingDays', 'as' => 'workingDays.'], function () {
        Route::post('/working-days/update/', 'WorkingDayController@update');
    });

    //Branch Routes
    Route::get('manage-branch', 'BranchController@index')->name('branch');
    Route::group(['prefix' => 'branch', 'as' => 'branch.'], function () {
        Route::post('datatable-data', 'BranchController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'BranchController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'BranchController@edit')->name('edit');
        Route::post('delete', 'BranchController@delete')->name('delete');
    });

    //Holiday
    Route::get('holiday', 'HolidayControllerController@index')->name('holiday');
    Route::group(['prefix' => 'holiday', 'as' => 'holiday.'], function () {
        Route::post('datatable-data', 'HolidayControllerController@get_datatable_data')->name('datatable.data');
        Route::post('store', 'HolidayControllerController@store')->name('store');
        Route::post('delete', 'HolidayControllerController@delete')->name('delete');
        Route::post('view', 'HolidayControllerController@view')->name('view');
    });

    //Allowance
    Route::get('hrm-allowance', 'AllowanceController@index')->name('hrm.allowance');
    Route::group(['prefix' => 'hrm-allowance', 'as' => 'hrm.allowance.'], function () {
        Route::post('datatable-data', 'AllowanceController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'AllowanceController@storeOrUpdate')->name('store.or.update');
        Route::post('delete', 'AllowanceController@delete')->name('delete');
        Route::post('edit', 'AllowanceController@edit')->name('edit');
    });
    //Deduction
    Route::get('hrm-deduction', 'DeductionController@index')->name('hrm.deduction');
    Route::group(['prefix' => 'hrm-deduction', 'as' => 'hrm.deduction.'], function () {
        Route::post('datatable-data', 'DeductionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'DeductionController@storeOrUpdate')->name('store.or.update');
        Route::post('delete', 'DeductionController@delete')->name('delete');
        Route::post('edit', 'DeductionController@edit')->name('edit');
    });

    //Shift
    Route::get('hrm-shift', 'ShiftController@index')->name('hrm.shift');
    Route::group(['prefix' => 'hrm-shift', 'as' => 'hrm.shift.'], function () {
        Route::post('datatable-data', 'ShiftController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ShiftController@storeOrUpdate')->name('store.or.update');
        Route::post('delete', 'ShiftController@delete')->name('delete');
        Route::post('edit', 'ShiftController@edit')->name('edit');
    });

    //leave-category
    Route::get('hrm-leave-category', 'LeaveCatController@index')->name('hrm.leave.category');
    Route::group(['prefix' => 'hrm-leave-category', 'as' => 'hrm.leave.category.'], function () {
        Route::post('datatable-data', 'LeaveCatController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'LeaveCatController@store_or_update_data')->name('store.or.update');
        Route::post('delete', 'LeaveCatController@delete')->name('delete');
        Route::post('edit', 'LeaveCatController@edit')->name('edit');
    });

    //Daily Attendance Store
    Route::get('attendance', 'AttendanceController@index')->name('attendance');
    Route::post('attendance-get-employees', 'AttendanceController@getEmployees')->name('attendance.get.employees');
    Route::post('attendance-store-or-update', 'AttendanceController@store_or_update')->name('attendance.store.or.update');

    //Leave management
    Route::get('leave', 'EmployeeLeaveController@index')->name('leave');
    Route::group(['prefix' => 'leave', 'as' => 'leave.'], function () {
        Route::post('datatable-data', 'EmployeeLeaveController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'EmployeeLeaveController@storeOrUpdate')->name('store.or.update');
        Route::post('delete', 'EmployeeLeaveController@delete')->name('delete');
        Route::post('edit', 'EmployeeLeaveController@edit')->name('edit');
        Route::post('view', 'EmployeeLeaveController@view')->name('view');
        Route::get('approve/{id}', 'EmployeeLeaveController@approve')->name('approve');
    });

    //Overtime
    Route::get('overtime', 'OvertimeController@index')->name('overtime');
    Route::group(['prefix' => 'overtime', 'as' => 'overtime.'], function () {
        Route::post('datatable-data', 'OvertimeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'OvertimeController@storeOrUpdate')->name('store.or.update');
        Route::post('delete', 'OvertimeController@delete')->name('delete');
        Route::post('edit', 'OvertimeController@edit')->name('edit');
        Route::post('view', 'OvertimeController@view')->name('view');
        Route::get('approve/{id}', 'OvertimeController@approve')->name('approve');
    });

    //Generate Salary
    Route::get('generate-salary', 'GenerateMonthlySalaryCOntroller@index')->name('generate.salary');
    Route::group(['prefix' => 'generate-salary', 'as' => 'generate.salary.'], function () {
        Route::post('datatable-data', 'GenerateMonthlySalaryCOntroller@get_datatable_data')->name('datatable.data');
        Route::get('create', 'GenerateMonthlySalaryCOntroller@create')->name('create');
        Route::post('store-or-update', 'GenerateMonthlySalaryCOntroller@storeOrUpdate')->name('store.or.update');
        Route::post('delete', 'GenerateMonthlySalaryCOntroller@delete')->name('delete');
        Route::post('edit', 'GenerateMonthlySalaryCOntroller@edit')->name('edit');
        Route::post('view', 'GenerateMonthlySalaryCOntroller@view')->name('view');
        Route::get('approve/{id}', 'GenerateMonthlySalaryCOntroller@approve')->name('approve');
    });
    Route::post('get-employees-data', 'GenerateMonthlySalaryCOntroller@getEmployees')->name('get.employees.data');

    //Employee Attendance Setup Routes
    Route::get('employee-attendance', 'EmployeeAttendanceController@index')->name('empAttendance');
    Route::group(['prefix' => 'employee-attendance', 'as' => 'empAttendance.'], function () {
        Route::post('datatable-data', 'EmployeeAttendanceController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'EmployeeAttendanceController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'EmployeeAttendanceController@edit')->name('edit');
        Route::post('delete', 'EmployeeAttendanceController@delete')->name('delete');
        Route::post('change-status', 'EmployeeAttendanceController@change_status')->name('change.status');
        Route::post('view', 'EmployeeAttendanceController@view')->name('view');
        Route::get('approve/{id}', 'EmployeeAttendanceController@approve')->name('approve');
    });

    //Attendance Report
    Route::get('attendance-report', 'AttendanceController@reportIndex')->name('attendance.report');
    Route::post('attendance-report-data', 'AttendanceController@reportData')->name('attendance.report.data');

    //Attendance Summery
    Route::get('attendance-summery', 'AttendanceController@summery')->name('attendance.summery');
    Route::post('attendance-summery-data', 'AttendanceController@summeryData')->name('attendance.summery.data');

    
    //Employee Attendance Setup Routes
    Route::get('employee-salary-payment', 'EmployeeSalaryPaymnetController@index')->name('employee.salary.payment');
    Route::group(['prefix' => 'employee-salary-payment', 'as' => 'employee.salary.payment.'], function () {
        Route::post('datatable-data', 'EmployeeSalaryPaymnetController@get_datatable_data')->name('datatable.data');
        Route::get('create', 'EmployeeSalaryPaymnetController@create')->name('create');
        Route::post('store-or-update', 'EmployeeSalaryPaymnetController@storeOrUpdate')->name('store.or.update');
        Route::post('update', 'EmployeeSalaryPaymnetController@update')->name('update');
        Route::get('edit/{id}', 'EmployeeSalaryPaymnetController@edit')->name('edit');
        Route::post('delete', 'EmployeeSalaryPaymnetController@delete')->name('delete');
        Route::post('view', 'EmployeeSalaryPaymnetController@view')->name('view');
        Route::get('approve/{id}', 'EmployeeSalaryPaymnetController@approve')->name('approve');
    });

    Route::post('get-employees-due', 'EmployeeSalaryPaymnetController@getEmployees')->name('get.employees.due');
});
