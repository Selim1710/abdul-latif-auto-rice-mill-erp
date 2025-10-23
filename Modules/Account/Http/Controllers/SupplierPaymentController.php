<?php

namespace Modules\Account\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Party\Entities\Party;
use App\Http\Controllers\BaseController;
use Modules\Account\Entities\Transaction;
use Modules\Account\Entities\SupplierPayment;
use Modules\Account\Http\Requests\SupplierPaymentFormRequest;

class SupplierPaymentController extends BaseController{
    private const payment = 'PAYMENT';
    public function __construct(SupplierPayment $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('supplier-payment-access')){
            $setTitle    = __('file.Party');
            $setSubTitle = __('file.Payment');
            $this->setPageData($setSubTitle,$setSubTitle,'far fa-money-bill-alt',[['name'=>$setTitle ],['name'=>$setSubTitle]]);
            return view('account::supplier-payment.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request){
        if($request->ajax() && permission('supplier-payment-access')){
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
                if(permission('supplier-payment-view')){
                    $action .= ' <a class="dropdown-item view_data" href="'.route("supplier.payment.show",$value->voucherNo).'">'.$this->actionButton('View').'</a>';
                }
                if (permission('supplier-payment-edit') and $value->status != 1) {
                    $action .= ' <a class="dropdown-item" href="'.route("supplier.payment.edit",$value->voucherNo).'">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('supplier-payment-status-change') and $value->status != 1){
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->voucherNo . '" data-name="' . $value->voucherNo . '" data-status="'.$value->status.'"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('supplier-payment-delete') and $value->status != 1) {
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
        if(permission('supplier-payment-add')){
            $setTitle    = __('file.Party');
            $setSubTitle = __('file.Payment');
            $this->setPageData($setSubTitle,$setSubTitle,'far fa-money-bill-alt',[['name'=>$setTitle],['name'=>$setSubTitle]]);
            $data = [
                'voucher_no' => self::payment.'-'.round(microtime(true)*1000),
                'parties'    => Party::all()
            ];
            return view('account::supplier-payment.create',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(SupplierPaymentFormRequest $request){
        if($request->ajax() && permission('supplier-payment-add')){
            DB::beginTransaction();
            try{
                $party = ChartOfHead::firstWhere(['master_head' => 3,'party_id' => $request->party_id]);
                $this->balanceDebit($party->id,$request->date,$request->voucher_no,$request->narration,$request->amount);
                $this->balanceCredit($request->account_id,$request->date,$request->voucher_no,$request->narration,$request->amount);
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
        if(permission('supplier-payment-view')){
            $this->setPageData('Party','Payment','far fa-money-bill-alt',[['name'=>'Party'],['name'=>'Payment']]);
            $data = $this->model->with('coh')->where(['voucher_no' => $voucher_no])->get();
            return view('account::supplier-payment.show',compact('data'));
        }else{
            return $this->access_blocked();
        }
    }
    public function edit($voucher_no){
        if(permission('supplier-payment-edit')){
            $this->setPageData('Party','Payment','far fa-money-bill-alt',[['name'=>'Party'],['name'=>'Payment']]);
            foreach ($this->model->with('coh')->where(['voucher_no' => $voucher_no])->get() as $value){
                $voucherNo = $value->voucher_no;
                $date      = $value->date;
                if($value->debit > 0) {
                    $party = $value->coh->party_id;
                    $amount= $value->debit;
                }
                if($value->credit > 0) {
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
            return view('account::supplier-payment.edit',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function update(SupplierPaymentFormRequest $request){
        if($request->ajax() && permission('supplier-payment-edit')){
            DB::beginTransaction();
            try {
                $voucher = $this->model->where(['voucher_no' => $request->update_id])->get();
                abort_if($voucher[0]->status == 1,404);
                $voucher->each->delete();
                $party = ChartOfHead::firstWhere(['master_head' => 3,'party_id' => $request->party_id]);
                $this->balanceDebit($party->id,$request->date,$request->voucher_no,$request->narration,$request->amount);
                $this->balanceCredit($request->account_id,$request->date,$request->voucher_no,$request->narration,$request->amount);
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
        if($request->ajax() && permission('supplier-payment-status-change')){
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
        if($request->ajax() && permission('supplier-payment-delete')){
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
    public function balanceDebit(int $partyId,$date,$voucherNo,$narration,float $amount) : void {
        Transaction::create([
            'chart_of_head_id' => $partyId,
            'date'             => $date,
            'voucher_no'       => $voucherNo,
            'voucher_type'     => self::payment,
            'narration'        => $narration,
            'debit'            => $amount,
            'credit'           => 0,
            'status'           => 3,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name
        ]);
    }
    public function balanceCredit(int $accountId,$date,$voucherNo,$narration,float $amount) : void {
        Transaction::create([
            'chart_of_head_id' => $accountId,
            'date'             => $date,
            'voucher_no'       => $voucherNo,
            'voucher_type'     => self::payment,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $amount,
            'status'           => 3,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name
        ]);
    }
}
