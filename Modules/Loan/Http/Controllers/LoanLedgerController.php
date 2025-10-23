<?php

namespace Modules\Loan\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Loan\Entities\LoanLedger;

class LoanLedgerController extends BaseController {
    public function __construct(LoanLedger $model){
        $this->model = $model;
    }
    public function index(){
        $setTitle = __('file.Loan');
        $this->setPageData($setTitle,$setTitle,'fas fa-university',[['name' => $setTitle]]);
        return view('loan::loanLedger.index');
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('loan-ledger-access')){
            $this->set_datatable_default_properties($request);
            $list  = $this->model->getDatatableList();
            $data  = [];
            $debit = $credit = $balance = 0;
            foreach ($list as $value) {
                $voucherNo  = $value->voucher_no;
                $debit  += $value->debit;
                $credit += $value->credit;
                $balance = $debit - $credit;
                $row     = [];
                $row[]   = $value->voucher_date;
                $row[]   = $value->description;
                $row[]   = $voucherNo;
                $row[]   = $value->debit ? number_format($value->debit,2, '.', ',') :  number_format(0,2, '.', ',');
                $row[]   = $value->credit ? number_format($value->credit,2, '.', ',') :  number_format(0,2, '.', ',');
                $row[]   = number_format(($balance),2, '.', ',');
                $data[]  = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
