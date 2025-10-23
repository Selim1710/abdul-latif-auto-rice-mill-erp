<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Account\Entities\Transaction;
use Modules\HRM\Entities\Employee;

class EmployeeLedgerController extends BaseController
{
    public function index()
    {
        if (permission('employee-ledger-access')) {
            $this->setPageData('Employee Ledger', 'Employee Ledger', 'fab fa-opencart', [['name' => 'Employee Ledger']]);
            $employees = Employee::with('coa')->get();
            return view('report::employee-ledger.index', compact('employees'));
        } else {
            return $this->access_blocked();
        }
    }


    public function report(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->all());
            if (permission('employee-ledger-access')) {
                $data = Transaction::where('chart_of_head_id', $request->employee_id)->where('status', 1)
                    ->whereBetween('date', [$request->from_date, $request->to_date])
                    ->get();
                return view('report::employee-ledger.data', compact('data'))->render();
            }
        }
    }
}
