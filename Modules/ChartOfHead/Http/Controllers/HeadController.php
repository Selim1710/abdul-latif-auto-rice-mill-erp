<?php

namespace Modules\ChartOfHead\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\Head;
use Modules\ChartOfHead\Http\Requests\HeadRequestForm;

class HeadController extends BaseController {
    public function __construct(Head $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('head-access')){
            $setTitle = __('file.Head');
            $this->setPageData($setTitle,$setTitle,'fas fa-th-list',[['name' => $setTitle]]);
            return view('chartofhead::head.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('head-access')){
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
                if(permission('head-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '" data-master_head = "' . $value->master_head . '"data-name = "'. $value->name .'">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('head-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = HEAD_TYPE[$value->type];
                $row[]  = MASTER_HEAD_LABEL[$value->master_head];
                $row[]  = $value->name;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function storeOrUpdate(HeadRequestForm $request){
       if(($request->ajax() && permission('head-add')) || ($request->ajax() && permission('head-edit'))){
           DB::beginTransaction();
           try{
               $collection   = collect($request->all())->except('_token')->merge(['type' => 1]);
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
        if($request->ajax() && permission('head-delete')){
            $result   = $this->model->find($request->id)->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function masterHeadWiseHead(Request $request){
        return response()->json(Head::where(['master_head' => $request->masterHead , 'type' => 1])->get(['id','name']));
    }
}
