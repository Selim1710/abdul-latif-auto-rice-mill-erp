<?php

namespace Modules\ChartOfHead\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\SubHead;
use Modules\ChartOfHead\Http\Requests\SubHeadRequestForm;

class SubHeadController extends BaseController {
    public function __construct(SubHead $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('sub-head-access')){
            $setTitle = __('file.Sub Head');
            $this->setPageData($setTitle,$setTitle,'fas fa-th-list',[['name' => $setTitle]]);
            return view('chartofhead::subHead.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('sub-head-access')){
            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('sub-head-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '" data-master_head = "' . $value->master_head . '" data-head_id = "' . $value->head_id . '" data-head_name = "' . $value->head->name . '"data-name = "'. $value->name .'">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('sub-head-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = HEAD_TYPE[$value->type];
                $row[]  = MASTER_HEAD_LABEL[$value->master_head];
                $row[]  = $value->head->name;
                $row[]  = $value->name;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function storeOrUpdate(SubHeadRequestForm $request){
        if(($request->ajax() && permission('sub-head-add')) || ($request->ajax() && permission('sub-head-edit'))){
            DB::beginTransaction();
            try{
                $collection   = collect($request->all())->except('_token')->merge(['type' => 2]);
                $collection   = $this->track_data($collection,$request->update_id);
                $result       = $this->model->updateOrCreate(['id' => $request->update_id],$collection->all());
                $output       = $this->store_message($result, $request->update_id);
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
        if($request->ajax() && permission('sub-head-delete')){
            $result   = $this->model->find($request->id)->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function headWiseSubHead(Request $request){
        return response()->json(SubHead::where(['head_id' => $request->headId , 'type' => 2])->get(['id','name']));
    }
}
