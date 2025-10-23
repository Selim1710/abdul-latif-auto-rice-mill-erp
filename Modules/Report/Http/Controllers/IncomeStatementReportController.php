<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Report\Entities\IncomeStatementReport;

class IncomeStatementReportController extends BaseController
{
    public function __construct(IncomeStatementReport $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('income-statement-report-access')) {
            $setTitle = __('file.Report');
            $setSubTitle = __('file.Income Statement Report');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-file-signature',[['name' => $setTitle,'link'=>'javascript::void();'],['name' => $setSubTitle]]);
            return view('report::income-statement.data');
        }else{
            return $this->access_blocked();
        }
    }
    public function report(Request $request)
    {
        if(permission('income-statement-report-access')) {
            $setTitle = __('file.Report');
            $setSubTitle = __('file.Income Statement Report');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-file-signature',[['name' => $setTitle,'link'=>'javascript::void();'],['name' => $setSubTitle]]);
            if(isset($request->date)){
                $date = $request->date;
            }else{
                $date = date('Y-m-d');
            }
            $data = [
                'date'                     => $date,
                'sales'                    => $this->model->sale($date)[0],
                'collections'                    => $this->model->collection($date)[0],
                'partyCollection'                    => $this->model->collection($date)[1],
                'totalPayment'                    => $this->model->supplierPayment($date)[1],
                'totalSale'                => $this->model->sale($date)[1],
                'tenant'                   => $this->model->tenant($date)[0],
                'totalTenant'              => $this->model->tenant($date)[1],
                'purchases'                => $this->model->purchase($date)[0],
                'payments'                 => $this->model->supplierPayment($date)[0],
                'totalPurchase'            => $this->model->purchase($date)[1],
                'incomePersonalLoan'       => $this->model->personalLoan($date)[0],
                'totalIncomePersonalLoan'  => $this->model->personalLoan($date)[1],
                'expensePersonalLoan'      => $this->model->personalLoan($date)[2],
                'totalExpensePersonalLoan' => $this->model->personalLoan($date)[3],
                'incomeOfficialLoan'       => $this->model->officialLoan($date)[0],
                'totalIncomeOfficialLoan'  => $this->model->officialLoan($date)[1],
                'expenseOfficialLoan'      => $this->model->officialLoan($date)[2],
                'totalExpenseOfficialLoan' => $this->model->officialLoan($date)[3],
                'machinePurchase'          => $this->model->machinePurchase($date)[0],
                'totalMachinePurchase'     => $this->model->machinePurchase($date)[1],
                'machineService'           => $this->model->machineService($date)[0],
                'totalMachineService'      => $this->model->machineService($date)[1],
                'transportService'         => $this->model->transportService($date)[0],
                'totalTransportService'    => $this->model->transportService($date)[1],
                'laborBill'                => $this->model->laborBill($date)[0],
                'totalLaborBill'           => $this->model->laborBill($date)[1],
                'expense'                  => $this->model->expense($date)[0],
                'totalExpense'             => $this->model->expense($date)[1],
                'cash'                     => $this->model->cash($date),
                'banks'                    => $this->model->bank(),
                'mobileBanks'              => $this->model->mobileBank(),
            ];
            return view('report::income-statement.index',$data);
        }else{
            return $this->access_blocked();
        }
    }
}
