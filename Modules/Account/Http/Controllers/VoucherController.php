<?php

namespace Modules\Account\Http\Controllers;

use App\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Transaction;
use Modules\Account\Entities\Voucher;
use Modules\Account\Http\Requests\VoucherFormRequest;
use Modules\ChartOfHead\Entities\ChildHead;
use Modules\ChartOfHead\Entities\Head;
use Modules\ChartOfHead\Entities\SubHead;

class VoucherController extends BaseController
{
    public function __construct(Voucher $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('voucher-access')) {
            $setTitle    = __('file.Accounts');
            $setSubTitle = __('file.Voucher List');
            $this->setPageData($setSubTitle, $setSubTitle, 'far fa-money-bill-alt', [['name' => $setTitle], ['name' => $setSubTitle]]);
            return view('account::voucher.index');
        } else {
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request)
    {
        if ($request->ajax() && permission('voucher-access')) {
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
                if (permission('voucher-view')) {
                    $action .= ' <a class="dropdown-item view_data" href="' . route("voucher.show", $value->voucherNo) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('voucher-edit') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item" href="' . route("voucher.edit", $value->voucherNo) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('voucher-status-change') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->voucherNo . '" data-name="' . $value->voucherNo . '" data-status="' . $value->status . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('voucher-delete') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->voucherNo . '" data-name="' . $value->voucherNo . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->chartOfHeadName;
                $row[]  = $value->date;
                $row[]  = $value->voucherNo;
                $row[]  = $value->voucherType;
                $row[]  = $value->narration;
                $row[]  = number_format($value->totalDebit, 2);
                $row[]  = number_format($value->totalCredit, 2);
                $row[]  = VOUCHER_APPROVE_STATUS_LABEL[$value->status];
                $row[]  = $value->createdBy;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function create()
    {
        // return 'hi';
        if (permission('voucher-add')) {
            $this->setPageData('Voucher', 'Voucher', 'far fa-money-bill-alt', [['name' => 'Accounts'], ['name' => 'Voucher']]);
            $data = [
                'heads'        => Head::where([['type', '=', 1]])->groupBy('id')->get(),
                'subHeads'     => SubHead::with('head')->where([['type', '=', 2]])->groupBy('id')->get(),
                'childHeads'   => ChildHead::with('subHead', 'subHead.head')->where([['type', '=', 3]])->groupBy('id')->get(),
                'voucher_no'   => round(microtime(true) * 1000),
            ];
            return view('account::voucher.create', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function store(VoucherFormRequest $request)
    {
        if ($request->ajax() && permission('voucher-add')) {
            DB::beginTransaction();
            try {
                $debitTransaction  = [];
                $creditTransaction = [];
                if ($request->has('debit')) {
                    foreach ($request->debit as $debit) {
                        if (!empty($debit['chart_of_head_id']) && !empty($debit['debit'])) {
                            $debitTransaction[] = [
                                'chart_of_head_id' => (int)$debit['chart_of_head_id'],
                                'date'             => $request->date,
                                'voucher_no'       => $request->voucher_no,
                                'voucher_type'     => $request->voucher_type,
                                'narration'        => $request->narration,
                                'debit'            => (float)$debit['debit'],
                                'credit'           => 0,
                                'status'           => 3,
                                'is_opening'       => 2,
                                'created_by'       => auth()->user()->name
                            ];
                        }
                    }
                }
                if ($request->has('credit')) {
                    foreach ($request->credit as $credit) {
                        if (!empty($debit['chart_of_head_id']) && !empty($credit['credit'])) {
                            $creditTransaction[] = [
                                'chart_of_head_id' => (int)$credit['chart_of_head_id'],
                                'date'             => $request->date,
                                'voucher_no'       => $request->voucher_no,
                                'voucher_type'     => $request->voucher_type,
                                'narration'        => $request->narration,
                                'debit'            => 0,
                                'credit'           => (float)$credit['credit'],
                                'status'           => 3,
                                'is_opening'       => 2,
                                'created_by'       => auth()->user()->name
                            ];
                        }
                    }
                }
                $this->model->insert($debitTransaction);
                $this->model->insert($creditTransaction);
                $output = ['status' => 'success', 'message' => $this->responseMessage('Data Saved')];
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function show($voucher_no)
    {
        if (permission('voucher-view')) {
            $this->setPageData('Voucher', 'Voucher', 'far fa-money-bill-alt', [['name' => 'Accounts'], ['name' => 'Voucher']]);
            $data = Transaction::with('coh')->where(['voucher_no' => $voucher_no])->get();
            return view('account::voucher.show', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }
    public function edit($voucher_no)
    {
        if (permission('voucher-edit')) {
            $this->setPageData('Voucher', 'Voucher', 'far fa-money-bill-alt', [['name' => 'Accounts'], ['name' => 'Voucher']]);
            $data = [
                'heads'        => Head::where([['type', '=', 1]])->groupBy('id')->get(),
                'subHeads'     => SubHead::with('head')->where([['type', '=', 2]])->groupBy('id')->get(),
                'childHeads'   => ChildHead::with('subHead', 'subHead.head')->where([['type', '=', 3]])->groupBy('id')->get(),
                'voucherData'  => Transaction::with('coh')->where(['voucher_no' => $voucher_no])->get()
            ];
            return view('account::voucher.edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(VoucherFormRequest $request)
    {
        if ($request->ajax() && permission('voucher-update')) {
            DB::beginTransaction();
            try {
                $voucher = Voucher::where(['voucher_no' => $request->update_id])->get();
                abort_if($voucher[0]->status == 1, 404);
                $voucher->each->delete();
                $debitTransaction  = [];
                $creditTransaction = [];
                if ($request->has('debit')) {
                    foreach ($request->debit as $debit) {
                        if (!empty($debit['chart_of_head_id']) && !empty($debit['debit'])) {
                            $debitTransaction[] = [
                                'chart_of_head_id' => (int)$debit['chart_of_head_id'],
                                'date'             => $request->date,
                                'voucher_no'       => $request->voucher_no,
                                'voucher_type'     => $request->voucher_type,
                                'narration'        => $request->narration,
                                'debit'            => (float)$debit['debit'],
                                'credit'           => 0,
                                'status'           => 3,
                                'is_opening'       => 2,
                                'created_by'       => auth()->user()->name
                            ];
                        }
                    }
                }
                if ($request->has('credit')) {
                    foreach ($request->credit as $credit) {
                        if (!empty($debit['chart_of_head_id']) && !empty($credit['credit'])) {
                            $creditTransaction[] = [
                                'chart_of_head_id' => (int)$credit['chart_of_head_id'],
                                'date'             => $request->date,
                                'voucher_no'       => $request->voucher_no,
                                'voucher_type'     => $request->voucher_type,
                                'narration'        => $request->narration,
                                'debit'            => 0,
                                'credit'           => (float)$credit['credit'],
                                'status'           => 3,
                                'is_opening'       => 2,
                                'created_by'       => auth()->user()->name
                            ];
                        }
                    }
                }
                $this->model->insert($debitTransaction);
                $this->model->insert($creditTransaction);
                $output = ['status' => 'success', 'message' => $this->responseMessage('Data Update')];
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function changeStatus(Request $request)
    {
        if ($request->ajax() && permission('voucher-status-change')) {
            DB::beginTransaction();
            try {
                $voucher = Voucher::where(['voucher_no' => $request->id])->get();
                abort_if($voucher[0]->status == 1, 404);
                $voucher->each->update([
                    'status' => 1
                ]);
                $output = ['status' => 'success', 'message' => 'Data Status Change Successfully'];
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax() && permission('voucher-delete')) {
            DB::beginTransaction();
            try {
                $voucher = Voucher::where(['voucher_no' => $request->id])->get();
                abort_if($voucher[0]->status == 1, 404);
                $voucher->each->delete();
                $output = ['status' => 'success', 'message' => 'Data Deleted Successfully'];
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
}
