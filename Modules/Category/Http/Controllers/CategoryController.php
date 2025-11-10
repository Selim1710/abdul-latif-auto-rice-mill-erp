<?php

namespace Modules\Category\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\Category\Http\Requests\CategoryRequestForm;

class CategoryController extends BaseController {
    public function __construct(Category $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('category-access')){
            $setTitle = __('file.Category');
            $this->setPageData($setTitle,$setTitle,'fas fa-th-list',[['name' => $setTitle]]);
            return view('category::index');
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('category-access')){
            if (!empty($request->category_name)) {
                $this->model->setCategoryName($request->category_name);
            }
            if (!empty($request->status)) {
                $this->model->setStatus($request->status);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                // if(permission('category-edit')){
                //     $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '" data-category_name="' . $value->category_name . '">'.$this->actionButton('Edit').'</a>';
                // }
                // if(permission('category-delete')){
                //    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->category_name . '">'.$this->actionButton('Delete').'</a>';
                // }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->category_name;
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = $value->created_by;
                $row[]  = $value->modified_by != null ? $value->modified_by : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function storeOrUpdate(CategoryRequestForm $request){
        if($request->ajax() && permission('category-add')){
            $collection   = collect($request->validated());
            $collection   = $this->track_data($collection,$request->update_id);
            $result       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
            $output       = $this->store_message($result, $request->update_id);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('category-delete')){
            $result   = $this->model->find($request->id)->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function changeStatus(Request $request){
        if($request->ajax() && permission('category-edit')){
            $result   = $this->model->find($request->id)->update(['status' => $request->status]);
            $output   = $result ? ['status' => 'success','message' => $this->responseMessage('Status Changed')] : ['status' => 'error','message' => $this->responseMessage('Status Changed Failed')];
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
