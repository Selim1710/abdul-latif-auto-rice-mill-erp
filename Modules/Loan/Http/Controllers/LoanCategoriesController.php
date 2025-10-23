<?php

namespace Modules\Loan\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Loan\Entities\LoanCategories;
use Modules\Loan\Http\Requests\LoanCategoriesFormRequest;

class LoanCategoriesController extends BaseController {
    public function __construct(LoanCategories $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('loan-categories-access')){
            $setTitle = __('file.Loan Categories');
            $this->setPageData($setTitle,$setTitle,'fas fa-university',[['name' => $setTitle]]);
            return view('loan::loanCategories.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('loan-categories-access')){
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action      = '';
                if(permission('loan-categories-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('loan-categories-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->name;
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = $value->created_by;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function storeOrUpdate(LoanCategoriesFormRequest $request){
        if($request->ajax() && permission('loan-categories-store-update')){
            DB::beginTransaction();
            try{
                $collection = collect($request->all())->except('_token')->merge(['created_by' => auth()->user()->name]);
                $collection = $this->track_data($collection,$request->update_id);
                $result     = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output     = $this->store_message($result, $request->update_id);
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
    public function edit(Request $request){
        if($request->ajax() && permission('loan-categories-edit')){
            return response()->json($this->model->find($request->id));
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('loan-categories-delete')){
            DB::beginTransaction();
            try{
                $this->model->find($request->id)->delete();
                $output = ['status' => 'success' , 'message' => 'Data Saved Successfully'];
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
}
