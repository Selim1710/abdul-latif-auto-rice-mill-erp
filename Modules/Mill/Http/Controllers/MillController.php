<?php

namespace Modules\Mill\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Mill\Entities\Mill;
use Modules\Mill\Http\Requests\MillRequestForm;


class MillController extends BaseController {
    private const ap = 'MILL-ASSET-PRICE';
    public function __construct(Mill $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('mill-access')){
            $setTitle = __('file.Mill');
            $this->setPageData($setTitle,$setTitle,'fas fa-clipboard',[['name' => $setTitle]]);
            return view('mill::index');
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request){
        if($request->ajax() && permission('mill-access')){
            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            if (!empty($request->address)) {
                $this->model->setAddress($request->address);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('mill-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('mill-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->name;
                $row[]  = $value->address;
                $row[]  = $value->asset_price;
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
    public function storeOrUpdateData(MillRequestForm $request){
        if($request->ajax() && permission('mill-add')){
            try{
                DB::beginTransaction();
                $collection   = collect($request->all());
                $collection   = $this->track_data($collection,$request->update_id);
                $result       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $millCohId    = ChartOfHead::updateOrCreate(
                    [
                        'mill_id'        => $result->id,
                        'classification' => 7
                    ],
                    [
                        'master_head'    => 5,
                        'type'           => 2,
                        'head_id'        => 15,
                        'mill_id'        => $result->id,
                        'name'           => $request->name.'('.$request->address.')',
                        'classification' => 7
                    ]
                );
                Transaction::updateOrCreate(
                    [
                        'chart_of_head_id' => $millCohId->id,
                        'voucher_type'     => self::ap,
                        'is_opening'       => 1
                    ],
                    [
                        'date'             => date('Y-m-d'),
                        'voucher_no'       => self::ap.'-'.round(microtime(true) * 1000),
                        'voucher_type'     => self::ap,
                        'narration'        => 'Mill Asset Price',
                        'debit'            => $request->asset_price,
                        'credit'           => 0,
                        'status'           => 1,
                        'created_by'       => auth()->user()->name,
                    ]
                );
                $output       = $this->store_message($result, $request->update_id);
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
        if($request->ajax() && permission('mill-edit')){
            $data   = $this->model->find($request->id);
            return response()->json($data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('mill-delete')){
            try{
                $this->model->find($request->id)->delete();
                $output = ['status' => 'success','message' => $this->responseMessage('Data Delete')];
            }catch(Exception $e){
                DB::rollBack();
                $output = ['status' => 'error','message' => $e->getMessage()];
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
