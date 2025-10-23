<?php

namespace Modules\Loan\Http\Controllers;

use Exception;
use App\Traits\UploadAble;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\Transaction;
use Modules\Bank\Entities\Bank;
use Modules\Loan\Entities\Loan;
use Modules\Loan\Entities\LoanCategories;
use Modules\Loan\Http\Requests\LoanFormRequest;

class LoanController extends BaseController {
    use UploadAble;
    private const VOUCHER_TYPE = "LOAN";
    public function __construct(Loan $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('loan-access')){
            $setTitle = __('file.Loan');
            $this->setPageData($setTitle,$setTitle,'fas fa-university',[['name' => $setTitle]]);
            $data = [
              'loanCategories' => LoanCategories::all(),
              'banks'          => Bank::all()
            ];
            return view('loan::loan.index',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('loan-access')){
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action      = '';
                if(permission('loan-edit') && $value->status == 2){
                    $action .= ' <a class="dropdown-item" href="'.route("loan.edit",$value->id).'">'.$this->actionButton('Edit').'</a>';
                }
                if (permission('loan-view')) {
                    $action .= ' <a class="dropdown-item" href="'.route("loan.show",$value->id).'">'.$this->actionButton('View').'</a>';
                }
                if(permission('loan-status-change') && $value->status == 2){
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if(permission('loan-delete') && $value->status == 2){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->monthlyInstallment . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = '<span class="label label-primary label-pill label-inline" style="min-width:100px !important;">'.$value->categoriesName.'</span>';
                $row[]  = $value->bankName;
                $row[]  = $value->bankBranch;
                $row[]  = $value->bankAccountNumber;
                $row[]  = number_format($value->totalAmount);
                $row[]  = number_format($value->monthlyInstallment);
                $row[]  = STATUS_LABEL[$value->status];
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
        if(permission('loan-add')){
            $setTitle = __('file.Loan Add');
            $this->setPageData($setTitle,$setTitle,'fas fa-university',[['name' => $setTitle]]);
            $data = [
                'loanCategories' => LoanCategories::all(),
//                'banks'          => Bank::all()
                'banks'          => Bank::all()
            ];
            return view('loan::loan.create',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(LoanFormRequest $request){
        if($request->ajax() && permission('loan-add')){
            DB::beginTransaction();
            try{
                $collection = collect($request->all())->except('_token')->merge(['created_by' => auth()->user()->name]);
                if($request->hasFile('document')){
                    $document     = $this->upload_file($request->file('document'),SALE_DOCUMENT_PATH);
                    $collection   = $collection->merge(compact('document'));
                }
                $this->model->create($collection->all());
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
    public function edit($id){
        if(permission('loan-edit')){
            $setTitle = __('file.Loan');
            $setSubTitle = __('file.Loan Edit');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-file',[['name'=>$setTitle,'link' => route('sale')],['name' => $setSubTitle]]);
            $data = [
                'loanCategories' => LoanCategories::all(),
                'banks'          => Bank::all(),
                'details'        => $this->model->with('loanCategories','bank')->find($id)
            ];
            return view('loan::loan.edit',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function show($id){
        if(permission('loan-view')){
            $setTitle = __('file.Loan');
            $setSubTitle = __('file.Loan Details');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-file',[['name'=>$setTitle,'link' => route('sale')],['name' => $setSubTitle]]);
            $data = $this->model->with('loanCategories','bank')->find($id);
            return view('loan::loan.details',compact('data'));
        }else{
            return $this->access_blocked();
        }
    }
    public function update(LoanFormRequest $request){
        if($request->ajax() && permission('loan-edit')){
            DB::beginTransaction();
            try{
                $collection = collect($request->all())->except('_token');
                $this->model->find($request->update_id)->update($collection->all());
                $output = ['status' => 'success' , 'message' => 'Data Updated Successfully'];
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
    public function changeStatus(Request $request){
        if($request->ajax() && permission('loan-status-change')){
            DB::beginTransaction();
            try{
                $loan             = $this->model->with('bank')->find($request->loan_id);
                $chartOfAccountId = ChartOfAccount::where(['name' => $loan->bank->bank_name , 'parent_name' => 'Cash At Bank' , 'level' => 4])->first();
                $description      = "Loan Debit Form " . $loan->bank->bank_name;
                $collection       = collect(['chart_of_account_id' => $chartOfAccountId->id,'voucher_no' => 'loan-'.floor(microtime(true) * 1000),'voucher_type' => self::VOUCHER_TYPE,'voucher_date' => $request->start_date,'description' => $description,'loan_id' => $request->loan_id,'credit' => $loan->total_amount,'debit' => 0,'is_opening' => 2,'posted' => 2,'approve' => 1,'created_by' => auth()->user()->name]);
                $loan->update([
                    'start_date'  => $request->start_date,
                    'status'      => $request->loan_status
                ]);
                Transaction::create($collection->all());
                $output = ['status' => 'success' , 'message' => 'Data Updated Successfully'];
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
    public function delete(Request $request){
        if($request->ajax() && permission('loan-delete')){
            DB::beginTransaction();
            try{
                $this->model->find($request->id)->delete();
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
