<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Transport\Entities\Truck;
use App\Http\Controllers\BaseController;
use Modules\Transport\Http\Requests\TruckRequest;

class TruckController extends BaseController{
    private const tap = 'TRUCK-ASSET-PRICE';
    public function __construct(Truck $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('truck-access')){
            $setTitle = __('file.Truck');
            $this->setPageData($setTitle,$setTitle,'fas fa-truck-pickup',[['name' => $setTitle]]);
            return view('transport::truck.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request){
        if($request->ajax() && permission('truck-access')){
            if (!empty($request->truck_no)) {
                $this->model->setTruckNo($request->truck_no);
            }
            if (!empty($request->status)) {
                $this->model->setStatus($request->status);
            }
            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('truck-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('truck-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->truck_no . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->truck_no;
                $row[]  = $value->asset_price;
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = $value->created_by;
                $row[]  = $value->modified_by ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Not Modified Yet</span>';
                $row[]  = $value->created_at ? date(config('settings.date_format'),strtotime($value->created_at)) : '';
                $row[]  = $value->modified_by ? date(config('settings.date_format'),strtotime($value->updated_at)) : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Update Date</span>';
                $row[]  = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function store_or_update_data(TruckRequest $request){
        if($request->ajax() && permission('truck-add')){
            $collection   = collect($request->validated());
            $collection   = $this->track_data($collection,$request->update_id);
            $result       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
            $truckCohId   = ChartOfHead::updateOrCreate(
                [
                    'truck_id'       => $result->id,
                    'classification' => 8
                ],
                [
                    'master_head'    => 5,
                    'type'           => 2,
                    'head_id'        => 16,
                    'truck_id'       => $result->id,
                    'name'           => 'truck('.$request->truck_no.')',
                    'classification' => 8
                ]
            );
            Transaction::updateOrCreate(
                [
                    'chart_of_head_id' => $truckCohId->id,
                    'voucher_type'     => self::tap,
                    'is_opening'       => 1
                ],
                [
                    'date'             => date('Y-m-d'),
                    'voucher_no'       => self::tap.'-'.round(microtime(true) * 1000),
                    'voucher_type'     => self::tap,
                    'narration'        => 'Truck Asset Price',
                    'debit'            => $request->asset_price,
                    'credit'           => 0,
                    'status'           => 1,
                    'created_by'       => auth()->user()->name,
                ]
            );
            $output       = $this->store_message($result, $request->update_id);
            $this->model->flushCache();
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function edit(Request $request){
        if($request->ajax() && permission('truck-edit')){
            $data   = $this->model->findOrFail($request->id);
            $output = $this->data_message($data);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('truck-delete')){
            $result   = $this->model->find($request->id)->delete();
            $output   = $this->delete_message($result);
            $this->model->flushCache();
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function change_status(Request $request){
        if($request->ajax() && permission('truck-delete')){
            $result   = $this->model->find($request->id)->update(['status' => $request->status]);
            $this->model->flushCache();
            $output   = $result ? ['status' => 'success','message' => $this->responseMessage('Approval Status')] : ['status' => 'error','message' => $this->responseMessage('Approval Status Failed')];
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
