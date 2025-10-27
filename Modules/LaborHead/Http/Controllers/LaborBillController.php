<?php

namespace Modules\LaborHead\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\LaborHead\Entities\LaborBill;
use Modules\LaborHead\Entities\LaborBillRate;
use Modules\LaborHead\Entities\LaborBillRateDetail;
use Modules\LaborHead\Entities\LaborHead;
use Modules\LaborHead\Http\Requests\LaborBillFormRequest;

class LaborBillController extends BaseController
{
    private const lb = 'LABOR-BILL';
    public function __construct(LaborBill $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('labor-bill-access')) {
            $setTitle    = __('file.Labor');
            $setSubTitle = __('file.Bill');
            $this->setPageData($setSubTitle, $setSubTitle, 'far fa-money-bill-alt', [['name' => $setTitle], ['name' => $setSubTitle]]);
            return view('laborhead::laborBill.index');
        } else {
            return $this->access_blocked();
        }
    }

    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('labor-bill-access')) {
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
                if (permission('labor-bill-show')) {
                    $action .= ' <a class="dropdown-item view_data" href="' . route("labor.bill.show", $value->invoiceNo) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('labor-bill-edit') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item" href="' . route("labor.bill.edit", $value->invoiceNo) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('labor-bill-status-change') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->invoiceNo . '" data-name="' . $value->invoiceNo . '" data-status="' . $value->status . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('labor-bill-delete') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->invoiceNo . '" data-name="' . $value->invoiceNo . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->date;
                $row[]  = $value->invoiceNo;
                $row[]  = $value->laborHead;
                $row[]  = number_format($value->amount, 2);
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
        if (permission('labor-bill-add')) {
            $this->setPageData('Labor Bill', 'Labor Bill', 'far fa-money-bill-alt', [['name' => 'Labor'], ['name' => 'Bill']]);
            $data = [
                'laborHeads'    => LaborHead::get(),
                'laborBillRates' => LaborBillRate::get(),
                'invoice_no'    => self::lb . '-' . round(microtime(true) * 1000),
            ];
            // return $data['laborBillRates'];

            return view('laborhead::laborBill.create', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function labourHeadWiseRate(Request $request)
    {
        // return $request;
        if (permission('labor-bill-add')) {
            $this->setPageData('Labor Bill', 'Labor Bill', 'far fa-money-bill-alt', [['name' => 'Labor'], ['name' => 'Bill']]);
            $data['laborBillRate']    = LaborBillRate::with('labour_bill_rate_details', 'labour_bill_rate_details.warehouse')->where('labor_head_id', $request->labor_head_id)->first();
            return view('laborhead::laborBill.labor_head_wise_rate', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function store(LaborBillFormRequest $request)
    {
        if ($request->ajax() && permission('labor-bill-add')) {
            DB::beginTransaction();
            try {
                $laborBill = [];
                if ($request->has('bill')) {
                    foreach ($request->bill as $value) {
                        if (!empty($value['labor_bill_rate_id']) && !empty($value['rate']) && !empty($value['qty']) && !empty($value['amount'])) {
                            $laborBill[] = [
                                'date'               => $request->date,
                                'invoice_no'         => $request->invoice_no,
                                'labor_head_id'      => $request->labor_head_id,
                                'labor_bill_rate_id' => $value['labor_bill_rate_id'],
                                'rate'               => $value['rate'],
                                'qty'                => $value['qty'],
                                'amount'             => $value['amount'],
                                'status'             => 3,
                                'narration'          => $request->narration,
                                'created_by'         => auth()->user()->name
                            ];
                        }
                    }
                }
                LaborBill::insert($laborBill);
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
    public function show($invoice_no)
    {
        if (permission('labor-bill-edit')) {
            $this->setPageData('Labor Bill', 'Labor Bill', 'far fa-money-bill-alt', [['name' => 'Labor'], ['name' => 'Bill']]);
            $data = LaborBill::with('laborHead', 'laborBillRate')->where(['invoice_no' => $invoice_no])->get();
            return view('laborhead::laborBill.details', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }
    public function edit($invoice_no)
    {
        if (permission('labor-bill-edit')) {
            $this->setPageData('Labor Bill', 'Labor Bill', 'far fa-money-bill-alt', [['name' => 'Labor'], ['name' => 'Bill']]);
            $data = [
                'edit'          => LaborBill::with('laborBillRate')->where(['invoice_no' => $invoice_no])->get(),
                'laborHeads'    => LaborHead::all(),
                'laborBillRates' => LaborBillRate::doesntHave('laborBill')->get(),
            ];
            return view('laborhead::laborBill.edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(LaborBillFormRequest $request)
    {
        if ($request->ajax() && permission('labor-bill-edit')) {
            DB::beginTransaction();
            try {
                LaborBill::where(['invoice_no' => $request->invoice_no])->get()->each->delete();
                $laborBill = [];
                if ($request->has('bill')) {
                    foreach ($request->bill as $value) {
                        if (!empty($value['labor_bill_rate_id']) && !empty($value['rate']) && !empty($value['qty']) && !empty($value['amount'])) {
                            $laborBill[] = [
                                'date'               => $request->date,
                                'invoice_no'         => $request->invoice_no,
                                'labor_head_id'      => $request->labor_head_id,
                                'labor_bill_rate_id' => $value['labor_bill_rate_id'],
                                'rate'               => $value['rate'],
                                'qty'                => $value['qty'],
                                'amount'             => $value['amount'],
                                'status'             => 3,
                                'narration'          => $request->narration,
                                'created_by'         => auth()->user()->name
                            ];
                        }
                    }
                }
                LaborBill::insert($laborBill);
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
                $voucher = LaborBill::where(['invoice_no' => $request->id])->get();
                $amount  = LaborBill::where(['invoice_no' => $request->id])->sum('amount');
                $coh     = ChartOfHead::firstWhere(['labor_head_id' => $voucher[0]->labor_head_id]);
                abort_if($voucher[0]->status == 1, 404);
                $voucher->each->update([
                    'status' => 1
                ]);
                $this->balanceCredit($coh->id, $voucher[0]->invoice_no, $voucher[0]->narration, $amount);
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
        if ($request->ajax() && permission('labor-bill-delete')) {
            DB::beginTransaction();
            try {
                $voucher = LaborBill::where(['invoice_no' => $request->id])->get();
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
    public function balanceCredit($cohId, $invoiceNo, $narration, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => date('Y-m-d'),
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => self::lb,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $paidAmount,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }
}
