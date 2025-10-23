<?php

namespace Modules\Party\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Party\Entities\Party;
use Modules\Party\Http\Requests\PartyRequestForm;

class PartyController extends BaseController {
    private const ob     = 'OPENING-BALANCE';
    public function __construct(Party $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('party-access')){
            $setTitle = __('file.Party');
            $this->setPageData($setTitle,$setTitle,'fas fa-th-list',[['name'=>$setTitle]]);
            $data = [
                'parties' => Party::all()
            ];
            return view('party::index',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('party-access')){
            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            if (!empty($request->mobile)) {
                $this->model->setMobile($request->mobile);
            }
            if (!empty($request->email)) {
                $this->model->setEmail($request->email);
            }
            $this->set_datatable_default_properties($request);
            $list   = $this->model->getDatatableList();
            $data   = [];
            $no     = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action      = '';
                if(permission('party-view')){
                    $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '"data-name="' . $value->name . '"data-company_name="' . $value->company_name . '"data-mobile="' . $value->mobile . '"data-address="' . $value->address . '"data-previous_balance="' . $value->previous_balance . '"data-balance_type="' . $value->balance_type . '"data-created_by="' . $value->created_by . '"data-modified_by="' . $value->modified_by . '">'.$this->actionButton('View').'</a>';
                }
                if(permission('party-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"data-name="' . $value->name . '"data-company_name="' . $value->company_name . '"data-mobile="' . $value->mobile . '"data-address="' . $value->address . '"data-previous_balance="' . $value->previous_balance . '"data-balance_type="' . $value->balance_type . '"data-created_by="' . $value->created_by . '"data-modified_by="' . $value->modified_by . '">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('party-delete')){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                }
                $partyBalance = $this->model->balance($value->id);
                $row     = [];
                $row[]   = $no;
                $row[]   = $value->company_name ? $value->name.' ('.$value->company_name.')' : $value->name;
                $row[]   = $value->address;
                $row[]   = $value->mobile;
                $row[]   = !empty($value->previous_balance) ? number_format($value->previous_balance,2) : '<span class="label label-info label-pill label-inline" style="min-width:100px !important;"></span>';
                $row[]   = BALANCE_TYPE_LABEL[$value->balance_type];
                $row[]   = number_format($partyBalance->totalDebit - $partyBalance->totalCredit,2);
                $row[]   = $value->created_by;
                $row[]   = action_button($action);
                $data[]  = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function storeOrUpdate(PartyRequestForm $request){
        if($request->ajax() && permission('party-add')){
            DB::beginTransaction();
            try {
                $collection  = collect($request->all())->except('_token','id')->merge(['status' => 1,'balance_type' => $request->balance_type ?? 3 ]);
                $collection  = $this->track_data($collection,$request->id);
                $party       = $this->model->updateOrCreate(
                                [
                                    'id' => $request->id
                                ],
                                $collection->all()
                );
                $chartOfHead = ChartOfHead::where(['party_id' => $party->id,'classification' => 2])->get();
                if($chartOfHead->count() == 0){
                    $currentAsset        = collect(['master_head' => 1 , 'type' => 3 , 'head_id' => 1, 'sub_head_id' => 21 , 'party_id' => $party->id , 'name' => $request->name , 'classification' => 2 ]);
                    $currentLiabilities  = collect(['master_head' => 3 , 'type' => 3 , 'head_id' => 8 , 'sub_head_id' => 27 , 'party_id' => $party->id , 'name' => $request->name , 'classification' => 2]);
                    ChartOfHead::insert([$currentAsset->all(),$currentLiabilities->all()]);
                }else{
                    foreach ($chartOfHead as $head){
                        $head->update([
                            'name' => $request->name
                        ]);
                    }
                }
                $this->previousBalanceAdd($party->id,$request->name,$request->previous_balance,$request->balance_type);
                $output = $this->store_message($party, $request->id);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error','message' => $e->getMessage()];
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request){
        if($request->ajax() && permission('party-delete')){
            $result   = $this->model->find($request->id)->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function previousBalanceAdd($partyId,$partyName,$previousBalance,$balanceType){
        $currentAssetParty                  = ChartOfHead::firstWhere(['master_head' => 1,'party_id' => $partyId]);
        $currentLiabilitiesParty            = ChartOfHead::firstWhere(['master_head' => 3,'party_id' => $partyId]);
        $currentAssetPartyTransaction       = Transaction::firstWhere(['chart_of_head_id' => $currentAssetParty->id,'voucher_type' => 'OPENING-BALANCE']);
        $currentLiabilitiesPartyTransaction = Transaction::firstWhere(['chart_of_head_id' => $currentLiabilitiesParty->id,'voucher_type' => 'OPENING-BALANCE']);
        if(!empty($currentAssetPartyTransaction)){
            $currentAssetPartyTransaction->delete();
        }
        if(!empty($currentLiabilitiesPartyTransaction)){
            $currentLiabilitiesPartyTransaction->delete();
        }
        if($balanceType == 1){
            Transaction::create([
                'chart_of_head_id' => $currentAssetParty->id,
                'date'             => date('Y-m-d'),
                'voucher_no'       => self::ob.'-'.round(microtime(true)*1000),
                'voucher_type'     => self::ob,
                'narration'        => $partyName.' Receivable Opening Balance',
                'debit'            => $previousBalance,
                'credit'           => 0,
                'status'           => 1,
                'is_opening'       => 1,
                'created_by'       => auth()->user()->name,
            ]);
        }
        if($balanceType == 2){
            Transaction::create([
                'chart_of_head_id' => $currentLiabilitiesParty->id,
                'date'             => date('Y-m-d'),
                'voucher_no'       => self::ob.'-'.round(microtime(true)*1000),
                'voucher_type'     => self::ob,
                'narration'        => $partyName.' Payable Opening Balance',
                'debit'            => 0,
                'credit'           => $previousBalance,
                'status'           => 1,
                'is_opening'       => 1,
                'created_by'       => auth()->user()->name,
            ]);
        }
    }
    public function due($partyId){
        $party = DB::table('chart_of_heads as coh')
                    ->join('transactions as t','coh.id','=','t.chart_of_head_id')
                    ->where(['coh.party_id' => $partyId,'t.status' => 1])
                    ->select(
                        DB::raw('SUM(t.debit) as totalDebit'),
                        DB::raw('SUM(t.credit) as totalCredit'),
                    )
                    ->get();
        return response()->json($party[0]->totalDebit - $party[0]->totalCredit);
    }
}
