<?php

namespace Modules\MobileBank\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\MobileBank\Entities\MobileBank;
use Modules\Account\Entities\ChartOfAccount;
use Modules\MobileBank\Http\Requests\MobileBankFormRequest;

class MobileBankController extends BaseController{
    private const CASH_AT_MOBILE_BANK = 'Cash At Mobile Bank';
    public function __construct(MobileBank $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('mobile-bank-access')){
            $setTitle = __('file.Mobile Bank List');
            $this->setPageData($setTitle,$setTitle,'fas fa-mobile-alt',[['name' => $setTitle]]);
            return view('mobilebank::index');
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request){
        if($request->ajax() && permission('mobile-bank-access')){
            if (!empty($request->bank_name)) {
                $this->model->setBankName($request->bank_name);
            }
            if (!empty($request->account_name)) {
                $this->model->setAccountName($request->account_name);
            }
            if (!empty($request->account_number)) {
                $this->model->setAccountNumber($request->account_number);
            }
            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('mobile-bank-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('mobile-bank-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->bank_name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->bank_name;
                $row[]  = $value->account_name;
                $row[]  = $value->account_number;
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function store_or_update_data(MobileBankFormRequest $request){
        if(($request->ajax() && permission('mobile-bank-add')) || ($request->ajax() && permission('mobile-bank-edit'))){
            DB::beginTransaction();
            try{
                $collection = collect($request->validated());
                $collection = $this->track_data($collection,$request->update_id);
                $result     = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output     = $this->store_message($result, $request->update_id);
                ChartOfHead::updateOrCreate(
                    [
                        'mobile_bank_id' => $result->id,
                        'classification' => 6
                    ],
                    [
                        'master_head'    => 1,
                        'type'           => 3,
                        'head_id'        => 2,
                        'sub_head_id'    => 26,
                        'mobile_bank_id' => $result->id,
                        'name'           => $request->account_name.'('.$request->account_number.')',
                        'classification' => 6
                    ]
                );
                $this->model->flushCache();
                DB::commit();
            }catch(Exception $e){
                DB::rollback();
                $output = ['status' => 'error','message' => $e->getMessage()];
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function edit(Request $request){
        if($request->ajax() && permission('mobile-bank-edit')){
            $data   = $this->model->findOrFail($request->id);
            $output = $this->data_message($data);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('mobile-bank-delete')){
            $bank   = $this->model->findOrFail($request->id);
            $result = $bank->delete();
            $output = ['status' => 'success','message' => $this->responseMessage('Data Delete')];
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
