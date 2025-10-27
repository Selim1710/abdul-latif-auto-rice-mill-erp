<?php

namespace Modules\LaborHead\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\LaborHead\Entities\LaborBill;
use Modules\LaborHead\Entities\LaborBillDetail;
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
                    $action .= ' <a class="dropdown-item view_data" href="' . route("labor.bill.show", $value->id) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('labor-bill-edit') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item" href="' . route("labor.bill.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('labor-bill-status-change') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '" data-status="' . $value->status . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('labor-bill-delete') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->date;
                $row[]  = $value->invoice_no;
                $row[]  = $value->laborHead->name ?? '';
                $row[]  = number_format($value->grand_total ?? 0);
                $row[]  = VOUCHER_APPROVE_STATUS_LABEL[$value->status];
                $row[]  = $value->created_by ?? '';
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
        // return $request;

        if ($request->ajax() && permission('labor-bill-add')) {
            DB::beginTransaction();
            try {
                $labour_bill = LaborBill::create([
                    'invoice_no'         => $request->invoice_no,
                    'date'               => $request->date,
                    'labor_head_id'      => $request->labor_head_id,
                    'grand_total'      => $request->grand_total,
                    'narration'          => $request->narration,
                    'status'             => 3,
                    'created_by'         => auth()->user()->name
                ]);

                if ($request->has('bill')) {
                    foreach ($request->bill as $value) {
                        if (!empty($value['labor_bill_rate_detail_id']) && !empty($value['rate']) && !empty($value['qty']) && !empty($value['amount'])) {
                            LaborBillDetail::create([
                                'labor_bill_id' => $labour_bill->id,
                                'labor_bill_rate_detail_id' => $value['labor_bill_rate_detail_id'] ?? '',
                                'warehouse_id' => $value['warehouse_id'] ?? '',
                                'rate'               => $value['rate'] ?? '',
                                'qty'                => $value['qty'] ?? '',
                                'amount'             => $value['amount'] ?? '',
                                'status'             => 3,
                                'created_by'         => auth()->user()->name
                            ]);
                        }
                    }
                }

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

    public function show($id)
    {
        if (permission('labor-bill-edit')) {
            $this->setPageData('Labor Bill', 'Labor Bill', 'far fa-money-bill-alt', [['name' => 'Labor'], ['name' => 'Bill']]);
            $labour_bill = LaborBill::with('laborHead', 'laborBillDetails', 'laborBillDetails.warehouse')->find($id);
            return view('laborhead::laborBill.details', compact('labour_bill'));
        } else {
            return $this->access_blocked();
        }
    }

    public function edit($id)
    {
        if (permission('labor-bill-edit')) {
            $this->setPageData('Labor Bill Edit', 'Labor Bill Edit', 'far fa-money-bill-alt', [['name' => 'Labor'], ['name' => 'Bill']]);
            $data = [
                'labour_bill'          => LaborBill::with('laborBillDetails')->find($id),
                'laborHeads'    => LaborHead::get(),
            ];
            return view('laborhead::laborBill.edit', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function update(LaborBillFormRequest $request)
    {
        // return $request;

        if ($request->ajax() && permission('labor-bill-edit')) {
            DB::beginTransaction();
            try {
                $labour_bill = LaborBill::find($request->labor_bill_id);

                $labour_bill->update([
                    'invoice_no'         => $request->invoice_no,
                    'date'               => $request->date,
                    'labor_head_id'      => $request->labor_head_id,
                    'grand_total'      => $request->grand_total,
                    'narration'          => $request->narration,
                    'status'             => 3,
                    'created_by'         => auth()->user()->name
                ]);

                if ($request->has('bill')) {
                    LaborBillDetail::where(['labor_bill_id' => $labour_bill->id])->delete();
                    foreach ($request->bill as $value) {
                        if (!empty($value['labor_bill_rate_detail_id']) && !empty($value['rate']) && !empty($value['qty']) && !empty($value['amount'])) {
                            LaborBillDetail::create([
                                'labor_bill_id' => $labour_bill->id,
                                'labor_bill_rate_detail_id' => $value['labor_bill_rate_detail_id'] ?? '',
                                'warehouse_id' => $value['warehouse_id'] ?? '',
                                'rate'               => $value['rate'] ?? '',
                                'qty'                => $value['qty'] ?? '',
                                'amount'             => $value['amount'] ?? '',
                                'status'             => 3,
                                'created_by'         => auth()->user()->name
                            ]);
                        }
                    }
                }
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
                $voucher = LaborBill::find($request->id);
                if (!empty($voucher)) {
                    $amount  = $voucher->grand_total ?? 0;
                    $coh     = ChartOfHead::firstWhere(['labor_head_id' => $voucher->labor_head_id]);
                    abort_if($voucher->status == 1, 404);
                    $voucher->update([
                        'status' => 1
                    ]);
                    $this->balanceCredit($coh->id, $voucher->id, $voucher->narration, $amount);
                    $output = ['status' => 'success', 'message' => 'Data Status Change Successfully'];
                }
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
                $labour_bill = LaborBill::find($request->id);
                abort_if($labour_bill->status == 1, 404);
                $labour_bill->delete();
                LaborBillDetail::where(['labor_bill_id' => $labour_bill->id])->delete();
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
