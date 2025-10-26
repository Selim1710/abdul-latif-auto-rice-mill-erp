<?php

namespace Modules\LaborHead\Http\Controllers;

use App\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\LaborHead\Entities\LaborHead;
use Modules\LaborHead\Http\Requests\LaborHeadFormRequest;

class LaborHeadController extends BaseController
{
    private const ob     = 'OPENING-BALANCE';
    public function __construct(LaborHead $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        $setTitle = __('file.Labor Head');
        $this->setPageData($setTitle, $setTitle, 'fas fa-user', [['name' => $setTitle]]);
        return view('laborhead::laborHead.index');
    }
    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('labor-head-access')) {
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
            $list              = $this->model->getDatatableList();
            $data              = [];
            $no                = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action      = '';
                if (permission('labor-head-edit')) {
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"data-name="' . $value->name . '"data-mobile="' . $value->mobile . '"data-previous_balance="' . $value->previous_balance . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('labor-head-delete')) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->name;
                $row[]  = $value->mobile;
                $row[]  = $value->previous_balance;
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = $value->created_by;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function storeOrUpdateData(LaborHeadFormRequest $request)
    {
        if ($request->ajax() && permission('labor-head-add')) {
            DB::beginTransaction();
            try {
                $collection = collect($request->all())->except('_token', 'update_id')->merge(['status' => 1, 'created_by' => auth()->user()->name]);
                $result     = $this->model->updateOrCreate(
                    [
                        'id' => $request->update_id
                    ],
                    $collection->all()
                );
                ChartOfHead::updateOrCreate(
                    [
                        'labor_head_id'    => $result->id,
                        'classification'   => 3
                    ],
                    [
                        'master_head'      => 3,
                        'type'             => 3,
                        'head_id'          => 8,
                        'sub_head_id'      => 29,
                        'labor_head_id'    => $result->id,
                        'name'             => $request->name,
                        'classification'   => 3
                    ]
                );
                $cohId = ChartOfHead::firstWhere(['labor_head_id' => $result->id, 'classification' => 3]);
                if (!empty($request->update_id)) {
                    $transaction = Transaction::firstWhere(['chart_of_head_id' => $cohId->id]);
                    if (!empty($transaction)) {
                        $transaction->delete();
                    }
                }
                if (!empty($request->previous_balance)) {
                    Transaction::create([
                        'chart_of_head_id' => $cohId->id,
                        'date'             => date('Y-m-d'),
                        'voucher_no'       => self::ob . '-' . round(microtime(true) * 1000),
                        'voucher_type'     => self::ob,
                        'narration'        => 'Labor Head Payable Balance',
                        'debit'            => 0,
                        'credit'           => $request->previous_balance,
                        'status'           => 1,
                        'is_opening'       => 1,
                        'created_by'       => auth()->user()->name,
                    ]);
                }
                $output  = ['status' => 'success', 'message' => $this->responseMessage('Data Saved')];
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output  = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax() && permission('labor-head-delete')) {
            $result   = $this->model->find($request->id)->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
}
