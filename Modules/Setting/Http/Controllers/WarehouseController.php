<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Setting\Entities\Warehouse;
use App\Http\Controllers\BaseController;
use Modules\Setting\Http\Requests\WarehouseFormRequest;

class WarehouseController extends BaseController{
    public function __construct(Warehouse $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('warehouse-access')){
            $setTitle = __('file.Company');
            $this->setPageData($setTitle,$setTitle,'fas fa-warehouse',[['name' => $setTitle]]);
            $deletable = self::DELETABLE;
            return view('setting::warehouse.index',compact('deletable'));
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request){
        if($request->ajax() && permission('warehouse-access')){
            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('warehouse-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('warehouse-delete') && $value->deletable == 2){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->name;
                $row[]  = $value->phone;
                $row[]  = $value->email;
                $row[]  = $value->address;
                $row[]  = permission('warehouse-edit') ? change_status($value->id,$value->status, $value->name) : STATUS_LABEL[$value->status];
                $row[]  = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function store_or_update_data(WarehouseFormRequest $request){
        if($request->ajax() && permission('warehouse-add')){
            $collection   = collect($request->validated());
            $collection   = $this->track_data($collection,$request->update_id);
            $result       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
            $output       = $this->store_message($result, $request->update_id);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function edit(Request $request){
        if($request->ajax() && permission('warehouse-edit')){
            $data   = $this->model->findOrFail($request->id);
            $output = $this->data_message($data);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('warehouse-delete')){
            $result   = $this->model->find($request->id)->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function change_status(Request $request){
        if($request->ajax() && permission('warehouse-edit')){
            $result   = $this->model->find($request->id)->update(['status' => $request->status]);
            $output   = $result ? ['status' => 'success','message' => $this->responseMessage('Status Changed')] : ['status' => 'error','message' => $this->responseMessage('Status Changed Failed')];
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
