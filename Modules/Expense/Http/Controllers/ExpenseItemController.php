<?php

namespace Modules\Expense\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Expense\Entities\ExpenseItem;
use Modules\Expense\Http\Requests\ExpenseItemFormRequest;


class ExpenseItemController extends BaseController{
    public function __construct(ExpenseItem $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('expense-item-access')){
            $setTitle    = __('file.Expense');
            $setSubTitle = __('file.Expense Item');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-money-check-alt',[['name'=>$setTitle,'link'=>'javascript::void();'],['name' => $setSubTitle]]);
            return view('expense::expense-item.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request){
        if($request->ajax() && permission('expense-item-access')){
            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action      = '';
                if(permission('expense-item-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('expense-item-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->name;
                $row[]  = EXPENSE_TYPE_LABEL[$value->expense_type];
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function store_or_update_data(ExpenseItemFormRequest $request){
        if($request->ajax() && permission('expense-item-add')){
            DB::beginTransaction();
            try{
                $collection   = collect($request->all())->merge(['status' => 1]);
                $collection   = $this->track_data($collection,$request->update_id);
                $result       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                ChartOfHead::updateOrCreate(
                    [
                        'expense_item_id'  => $result->id,
                        'classification'   => 4
                    ],
                    [
                        'master_head'      => 7,
                        'type'             => 2,
                        'head_id'          => $request->expense_type == 1 ? 19 : 20,
                        'expense_item_id'  => $result->id,
                        'name'             => $request->name.'('.EXPENSE_TYPE_VALUE[$request->expense_type].')',
                        'classification'   => 4
                    ]
                );
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
    public function edit(Request $request){
        if($request->ajax()){
            if(permission('expense-item-edit')){
                $data   = $this->model->findOrFail($request->id);
                $output = $this->data_message($data);
            }else{
                $output = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('expense-item-delete')){
            $result   = $this->model->findOrFail($request->id)->delete();
            $output   = $this->delete_message($result);
            $this->model->flushCache();
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
