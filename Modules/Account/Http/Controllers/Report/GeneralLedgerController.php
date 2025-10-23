<?php

namespace Modules\Account\Http\Controllers\Report;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use Modules\Account\Entities\Transaction;

class GeneralLedgerController extends BaseController
{
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }


    public function index()
    {
        if(permission('general-ledger-access')){
            $setTitle = __('file.Accounts');
            $setSubTitle = __('file.Report');
            $setChildTitle = __('file.General Ledger');
            $this->setPageData($setChildTitle,$setChildTitle,'far fa-money-bill-alt',[['name'=>$setTitle,'link'=>'javascript::void(0);'],['name'=>$setSubTitle,'link'=>'javascript::void(0);'],['name'=>$setChildTitle]]);
            $general_heads      = DB::table('chart_of_accounts')->where([['general_ledger',1],['status',1]])->get();
            return view('account::report.general-ledger.index',compact('general_heads'));
        }else{
            return $this->access_blocked();
        }
    }

    public function transaction_heads(Request $request)
    {
        if($request->ajax())
        {
            $output = '';
            $transaction_heads = DB::table('chart_of_accounts')->where([['parent_name',$request->parent_name],['status',1]])->get();
            if(!$transaction_heads->isEmpty())
            {
                $output .= '<option value=""></option>';
                foreach ($transaction_heads as $key => $value) {
                    $output .= '<option value="'.$value->id.'" data-name="'.$value->name.'">'.$value->name.'</option>';
                }
            }
            return $output;
        }
    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            $start_date          = $request->start_date ? $request->start_date : date('Y-m-d');
            $end_date            = $request->end_date ? $request->end_date : date('Y-m-d');

            $bank_name           = $request->bank_name;
            if($request->transaction_head){
                $report_data = DB::table('transactions as t')
                                ->selectRaw('t.id,t.voucher_no, t.voucher_type, t.voucher_date,
                                t.debit, t.credit, t.approve, t.chart_of_account_id, coa.name as account_name, coa.parent_name,
                                 coa.type,t.description')
                                ->leftJoin('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
                                ->whereDate('t.voucher_date','>=',$start_date)
                                ->whereDate('t.voucher_date','<=',$end_date)
                                ->where('t.chart_of_account_id',$request->transaction_head)
                                ->where('t.approve',1);

                $report_data = $report_data->get();

                $pre_balance_data = DB::table('transactions')
                                    ->selectRaw('sum(debit) as predebit, sum(credit) as precredit')
                                    ->where('voucher_date','<',$end_date)
                                    ->where('chart_of_account_id',$request->transaction_head)
                                    ->where('approve',1)
                                    ->first();

                $pre_balance = $pre_balance_data->predebit - $pre_balance_data->precredit;
                return view('account::report.general-ledger.report',compact('start_date','end_date',
                'pre_balance','report_data','bank_name'))->render();
            }

        }
    }
}
