<?php

namespace Modules\Account\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\OpeningBalance;
use Modules\Account\Entities\Transaction;
use Modules\Account\Http\Requests\OpeningBalanceFormRequest;
use Modules\ChartOfHead\Entities\ChildHead;
use Modules\ChartOfHead\Entities\Head;
use Modules\ChartOfHead\Entities\SubHead;

class OpeningBalanceController extends BaseController {
   private const ob = 'OPENING-BALANCE';
   public function __construct(OpeningBalance $model){
       return $this->model = $model;
   }
    public function index(){
        if(permission('opening-balance-access')){
            $setTitle    = __('file.Accounts');
            $setSubTitle = __('file.Opening Balance');
            $this->setPageData($setSubTitle,$setSubTitle,'far fa-money-bill-alt',[['name'=>$setTitle],['name'=>$setSubTitle]]);
            return view('account::opening-balance.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request){
        if($request->ajax() && permission('opening-balance-access')){
            if (!empty($request->from_date)) {
                $this->model->setFromDate($request->from_date);
            }
            if (!empty($request->to_date)) {
                $this->model->setToDate($request->to_date);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('opening-balance-view')){
                    $action .= ' <a class="dropdown-item view_data" href="'.route("opening.balance.show",$value->id).'">'.$this->actionButton('View').'</a>';
                }
                if (permission('opening-balance-edit') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item" href="'.route("opening.balance.edit",$value->id).'">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('opening-balance-change-status') && $value->status != 1){
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->voucherNo . '" data-status="'.$value->status.'"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('opening-balance-delete') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->voucherNo . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->chartOfHeadName;
                $row[]  = $value->date;
                $row[]  = $value->voucherNo;
                $row[]  = '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">'.$value->voucherType.'</span>';
                $row[]  = $value->narration;
                $row[]  = number_format($value->debit,2);
                $row[]  = number_format($value->credit,2);
                $row[]  = VOUCHER_APPROVE_STATUS_LABEL[$value->status];
                $row[]  = $value->createdBy;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function create(){
        if(permission('opening-balance-add')){
            $this->setPageData('Opening Balance','Opening Balance','far fa-money-bill-alt',[['name'=>'Accounts'],['name'=>'Opening Balance']]);
            $data = [
                'heads'        => Head::where([['type','=',1]])->whereNotIn('classification',[2,3,4])->groupBy('id')->get(),
                'subHeads'     => SubHead::with('head')->where([['type','=',2]])->whereNotIn('classification',[2,3,4])->groupBy('id')->get(),
                'childHeads'   => ChildHead::with('subHead','subHead.head')->where([['type','=',3]])->whereNotIn('classification',[2,3,4])->groupBy('id')->get(),
                'voucher_no'   => self::ob.'-'.round(microtime(true) * 1000),
            ];
            return view('account::opening-balance.create',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function storeOrUpdate(OpeningBalanceFormRequest $request){
       if($request->ajax() && permission('opening-balance-add')){
           DB::beginTransaction();
           try{
               Transaction::updateOrCreate(
                   [
                       'chart_of_head_id'    => $request->chart_of_head_id,
                       'voucher_type'        => self::ob,
                       'is_opening'          => 1,
                   ],
                   [
                       'date'                => $request->date,
                       'voucher_no'          => $request->voucher_no,
                       'narration'           => $request->narration??'Opening Balance '.$request->amount,
                       'debit'               => $request->balance_type == 1 ? $request->amount : 0,
                       'credit'              => $request->balance_type == 2 ? $request->amount : 0,
                       'status'              => 3,
                       'created_by'          => auth()->user()->name,
                   ]
               );
               $output = ['status' => 'success' , 'message' => $this->responseMessage('Data Saved')];
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
    public function show($id){
        if(permission('voucher-view')){
            $this->setPageData('Voucher','Voucher','far fa-money-bill-alt',[['name'=>'Accounts'],['name'=>'Voucher']]);
            $data = $this->model->with('coh')->where(['id' => $id])->get();
            return view('account::opening-balance.show',compact('data'));
        }else{
            return $this->access_blocked();
        }
    }
    public function edit($id){
        if(permission('opening-balance-add')){
            $this->setPageData('Opening Balance','Opening Balance','far fa-money-bill-alt',[['name'=>'Accounts'],['name'=>'Opening Balance']]);
            $data = [
                'heads'        => Head::where([['type','=',1]])->whereNotIn('classification',[2,3,4])->groupBy('id')->get(),
                'subHeads'     => SubHead::with('head')->where([['type','=',2]])->whereNotIn('classification',[2,3,4])->groupBy('id')->get(),
                'childHeads'   => ChildHead::with('subHead','subHead.head')->where([['type','=',3]])->whereNotIn('classification',[2,3,4])->groupBy('id')->get(),
                'edit'         => Transaction::findOrFail($id),
            ];
            return view('account::opening-balance.edit',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function changeStatus(Request $request){
        if($request->ajax() && permission('opening-balance-change-status')){
            DB::beginTransaction();
            try{
                $voucher = OpeningBalance::where(['id' => $request->id])->get();
                abort_if($voucher[0]->status == 1,404);
                $voucher->each->update([
                    'status' => 1
                ]);
                $output = ['status' => 'success' , 'message' => 'Data Status Change Successfully'];
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
        if($request->ajax() && permission('opening-balance-delete')){
            DB::beginTransaction();
            try{
                $voucher = OpeningBalance::where(['id' => $request->id])->get();
                abort_if($voucher[0]->status == 1,404);
                $voucher->each->delete();
                $output = ['status' => 'success' , 'message' => 'Data Deleted Successfully'];
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
