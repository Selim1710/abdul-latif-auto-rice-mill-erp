<?php

namespace Modules\Account\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\ChartOfHead;
use App\Http\Controllers\BaseController;
use Modules\Account\Entities\Transaction;
use Modules\Account\Entities\ReceivePayment;
use Modules\Account\Http\Requests\CustomerReceiveFormRequest;
use Modules\Party\Entities\Party;

class CustomerReceiveController extends BaseController{
    private const collection = 'COLLECTION';
    public function __construct(ReceivePayment $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('customer-receive-access')){
            $setTitle    = __('file.Party');
            $setSubTitle = __('file.Collection');
            $this->setPageData($setSubTitle,$setSubTitle,'far fa-money-bill-alt',[['name'=>$setTitle],['name'=>$setSubTitle]]);
            return view('account::customer-receive.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request){
        if($request->ajax() && permission('customer-receive-access')){
            if (!empty($request->from_date)) {
                $this->model->setFromDate($request->from_date);
            }
            if (!empty($request->to_date)) {
                $this->model->setToDate($request->to_date);
            }
            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('customer-receive-view')){
                    $action .= ' <a class="dropdown-item view_data" href="'.route("customer.receive.show",$value->voucherNo).'">'.$this->actionButton('View').'</a>';
                }
                if (permission('customer-receive-edit') and $value->status != 1) {
                    $action .= ' <a class="dropdown-item" href="'.route("customer.receive.edit",$value->voucherNo).'">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('customer-receive-status-change') and $value->status != 1){
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->voucherNo . '" data-name="' . $value->voucherNo . '" data-status="'.$value->status.'"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('customer-receive-delete') and $value->status != 1) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->voucherNo . '" data-name="' . $value->voucherNo . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->chartOfHeadName;
                $row[]  = $value->date;
                $row[]  = $value->voucherNo;
                $row[]  = $value->voucherType;
                $row[]  = $value->narration;
                $row[]  = number_format($value->totalDebit,2);
                $row[]  = number_format($value->totalCredit,2);
                $row[]  = VOUCHER_APPROVE_STATUS_LABEL[$value->status];
                $row[]  = $value->createdBy;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function create(){
        if(permission('customer-receive-add')){
            $setTitle    = __('file.Party');
            $setSubTitle = __('file.Receive');
            $this->setPageData($setSubTitle,$setSubTitle,'far fa-money-bill-alt',[['name'=>$setTitle],['name'=>$setSubTitle]]);
            $data = [
              'voucher_no' => self::collection.'-'.round(microtime(true)*1000),
              'parties'    => Party::all()
            ];
            return view('account::customer-receive.create',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(CustomerReceiveFormRequest $request){
        if($request->ajax() && permission('customer-receive-add')){
            DB::beginTransaction();
            try{
                $party = ChartOfHead::firstWhere(['master_head' => 1,'party_id' => $request->party_id]);
                $this->balanceDebit($request->account_id,$request->date,$request->voucher_no,$request->narration,$request->amount);
                $this->balanceCredit($party->id,$request->date,$request->voucher_no,$request->narration,$request->amount);
                $output = ['status' => 'success' , 'message' => $this->responseMessage('Data Saved')];
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
    public function show($voucher_no){
        if(permission('customer-receive-view')){
            $this->setPageData('Party','Collection','far fa-money-bill-alt',[['name'=>'Party'],['name'=>'Collection']]);
            $data = $this->model->with('coh')->where(['voucher_no' => $voucher_no])->get();
            return view('account::customer-receive.show',compact('data'));
        }else{
            return $this->access_blocked();
        }
    }
    public function edit($voucher_no){
        if(permission('customer-receive-edit')){
            $this->setPageData('Party','Collection','far fa-money-bill-alt',[['name'=>'Party'],['name'=>'Collection']]);
            foreach ($this->model->with('coh')->where(['voucher_no' => $voucher_no])->get() as $value){
                $voucherNo = $value->voucher_no;
                $date      = $value->date;
                if($value->credit > 0) {
                    $party = $value->coh->party_id;
                    $amount= $value->credit;
                }
                if($value->debit > 0) {
                    $paymentMethodCheck = ChartOfHead::findOrFail($value->chart_of_head_id);
                    if($paymentMethodCheck->classification == 1){
                        $paymentMethod = 1;
                    }elseif ($paymentMethodCheck->classification == 5){
                        $paymentMethod = 2;
                    }else{
                        $paymentMethod = 3;
                    }
                    $account = $value->chart_of_head_id;
                }
                $narration  = $value->narration;
            }
            $data = [
                'voucherNo'     => $voucherNo,
                'date'          => $date,
                'partyId'       => $party,
                'paymentMethod' => $paymentMethod,
                'account'       => $account,
                'amount'        => $amount,
                'narration'     => $narration,
                'parties'       => Party::all()
            ];
            return view('account::customer-receive.edit',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function update(CustomerReceiveFormRequest $request){
        if($request->ajax() && permission('supplier-payment-edit')){
            DB::beginTransaction();
            try {
                $voucher = $this->model->where(['voucher_no' => $request->update_id])->get();
                abort_if($voucher[0]->status == 1,404);
                $voucher->each->delete();
                $party = ChartOfHead::firstWhere(['master_head' => 1,'party_id' => $request->party_id]);
                $this->balanceDebit($request->account_id,$request->date,$request->voucher_no,$request->narration,$request->amount);
                $this->balanceCredit($party->id,$request->date,$request->voucher_no,$request->narration,$request->amount);
                $output = ['status' => 'success' , 'message' => $this->responseMessage('Data Update')];
                DB::commit();
            }catch (Exception $e){
                DB::rollBack();
                $output = ['status' => 'error','message' => $e->getMessage()];
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function changeStatus(Request $request){
        if($request->ajax() && permission('customer-receive-status-change')){
            DB::beginTransaction();
            try{
                $voucher = $this->model->where(['voucher_no' => $request->id])->get();
                abort_if($voucher[0]->status == 1,404);
                $voucher->each->update([
                    'status' => 1
                ]);
                $output = ['status' => 'success' , 'message' => 'Data Status Change Successfully'];
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
    public function delete(Request $request){
        if($request->ajax() && permission('customer-receive-delete')){
            DB::beginTransaction();
            try{
                $voucher = $this->model->where(['voucher_no' => $request->id])->get();
                abort_if($voucher[0]->status == 1,404);
                $voucher->each->delete();
                $output  = ['status' => 'success' , 'message' => 'Data Deleted Successfully'];
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
    public function balanceDebit(int $accountId,$date,$voucherNo,$narration,float $amount) : void {
        Transaction::create([
            'chart_of_head_id' => $accountId,
            'date'             => $date,
            'voucher_no'       => $voucherNo,
            'voucher_type'     => self::collection,
            'narration'        => $narration,
            'debit'            => $amount,
            'credit'           => 0,
            'status'           => 3,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name
        ]);
    }
    public function balanceCredit(int $partyId,$date,$voucherNo,$narration,float $amount) : void {
        Transaction::create([
            'chart_of_head_id' => $partyId,
            'date'             => $date,
            'voucher_no'       => $voucherNo,
            'voucher_type'     => self::collection,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $amount,
            'status'           => 3,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name
        ]);
    }
}
