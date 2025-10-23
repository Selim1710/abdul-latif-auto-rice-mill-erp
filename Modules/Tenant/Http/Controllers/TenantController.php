<?php

namespace Modules\Tenant\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Http\Requests\TenantRequestForm;

class TenantController extends BaseController {
    public function __construct(Tenant $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('tenant-access')){
            $setTitle = __('file.Tenant');
            $this->setPageData($setTitle,$setTitle,'fas fa-user-check',[['name'=>$setTitle]]);
            return view('tenant::tenant.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('tenant-access')){
            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            if (!empty($request->mobile)) {
                $this->model->setMobile($request->mobile);
            }
            if (!empty($request->status)) {
                $this->model->setStatus($request->status);
            }
            $this->set_datatable_default_properties($request);
            $list  = $this->model->getDatatableList();
            $data  = [];
            $no    = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action      = '';
                if(permission('tenant-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                }if(permission('tenant-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->name;
                $row[]  = $value->mobile;
                $row[]  = permission('tenant-edit') ? change_status($value->id,$value->status, $value->name) : STATUS_LABEL[$value->status];
                $row[]  = '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">'.$value->created_by.'</span>';
                $row[]  = $value->modified_by == '' ? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Not Modified Yet</span>' : '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">'.$value->modified_by.'</span>' ;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function storeOrUpdateData(TenantRequestForm $request){
        if($request->ajax() && permission('tenant-add')){
            DB::beginTransaction();
            try {
                $collection = collect($request->all())->except('_token')->merge(['created_by' => auth()->user()->name]);
                $collection = $this->track_data($collection,$request->update_id);
                $result     = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                ChartOfHead::updateOrCreate(
                    [
                        'tenant_id'      => $result->id,
                        'classification' => 9
                    ],
                    [
                        'master_head'    => 1,
                        'type'           => 3,
                        'head_id'        => 1,
                        'sub_head_id'    => 23,
                        'tenant_id'      => $result->id,
                        'name'           => $request->name.'('.$request->mobile.')',
                        'classification' => 9
                    ]
                );
                $output     = $this->store_message($result, $request->update_id);
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                $output = ['status' => 'error','message' => $e->getMessage()];
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function edit(Request $request){
        if($request->ajax() && permission('tenant-edit')){
            $data   = $this->model->findOrFail($request->id);
            $output = $this->data_message($data);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('tenant-delete')){
            $result   = $this->model->findOrFail($request->id)->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function changeStatus(Request $request){
        if($request->ajax() && permission('tenant-change-status')){
            $result   = $this->model->findOrFail($request->id)->update(['status' => $request->status]);
            $output   = $result ? ['status' => 'success','message' => $this->responseMessage('Approval Status')] : ['status' => 'error','message' => $this->responseMessage('Approval Status Failed')];
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
