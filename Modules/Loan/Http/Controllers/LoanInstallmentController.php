<?php

namespace Modules\Loan\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\Transaction;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanInstallment;
use Modules\Loan\Http\Requests\LoanInstallmentFormRequest;

class LoanInstallmentController extends BaseController {
    public function __construct(LoanInstallment $model){
        $this->model = $model;
    }
    private const VOUCHER_TYPE = "LOAN";
    public function index(){
        $setTitle = __('file.Loan');
        $this->setPageData($setTitle,$setTitle,'fas fa-university',[['name' => $setTitle]]);
        return view('loan::loanInstallment.index');
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('loan-installment-access')){
            $this->set_datatable_default_properties($request);
            $list  = $this->model->getDatatableList();
            $data  = [];
            $debit = $credit = $balance = 0;
            foreach ($list as $value) {
                $voucherNo  = $value->voucher_no;
                $debit     += $value->debit;
                $credit    += $value->credit;
                $balance    = $debit - $credit;
                $row        = [];
                $row[]      = $value->voucher_date;
                $row[]      = $value->description;
                $row[]      = $voucherNo;
                $row[]      = $value->debit ? number_format($value->debit,2, '.', ',') :  number_format(0,2, '.', ',');
                $row[]      = $value->credit ? number_format($value->credit,2, '.', ',') :  number_format(0,2, '.', ',');
                $row[]      = number_format(($balance),2, '.', ',');
                $data[]     = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function create(){
        if(permission('loan-installment-add')){
            $setTitle = __('file.Loan Installment Add');
            $this->setPageData($setTitle,$setTitle,'fas fa-university',[['name' => $setTitle]]);
            $data = [
                'voucher_no'              => date('ymd').rand(1,999),
                'loans'                   => Loan::with('loanCategories','bank')->where(['status' => 1])->get(),
                'transactional_accounts'  => ChartOfAccount::whereIn('parent_name' , ['Cash At Mobile Bank','Cash At Bank','Cash & Cash Equivalent'])->where(['transaction' => 1 , 'status' => 1])->orderBy('id','asc')->get(),
            ];
            return view('loan::loanInstallment.create',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(LoanInstallmentFormRequest $request){
        if($request->ajax() && permission('loan-installment-add')){
            DB::beginTransaction();
            try{
                $transactionData = [];
                if($request->has('loan')){
                    foreach ($request->loan as $value){
                        $transactionData[] = [
                            'chart_of_account_id' => $value['debitId'],
                            'voucher_no'          => $request->voucher_no,
                            'voucher_type'        => self::VOUCHER_TYPE,
                            'voucher_date'        => $request->voucher_date,
                            'description'         => $request->remarks,
                            'debit'               => $value['debitAmount'],
                            'credit'              => 0,
                            'loan_id'             => $request->loan_id,
                            'is_opening'          => 2,
                            'posted'              => 1,
                            'approve'             => 3
                        ];
                    }
                }
                Transaction::create($transactionData->all());
                $output = ['status' => 'success' , 'message' => 'Data Saved Successfully'];
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                $output = ['status' => 'error' , 'message' => $e->getMessage()];
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function edit(){

    }
    public function update(){

    }
    public function changeStatus(Request $request){
        if($request->ajax() && permission('loan-installment-status-change')){

        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(){

    }
}
