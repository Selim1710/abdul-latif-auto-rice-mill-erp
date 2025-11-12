<?php

namespace Modules\Account\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Expense;
use Modules\Account\Entities\Transaction;
use Modules\Account\Http\Requests\ExpenseFormRequest;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Expense\Entities\ExpenseItem;

class ExpenseController extends BaseController
{
    private const expense = 'EXPENSE';
    public function __construct(Expense $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('expense-access')) {
            $setTitle    = __('file.Expense');
            $setSubTitle = __('file.Expense');
            $this->setPageData($setSubTitle, $setSubTitle, 'far fa-money-bill-alt', [['name' => $setTitle], ['name' => $setSubTitle]]);
            return view('account::expense.index');
        } else {
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request)
    {
        if ($request->ajax() && permission('expense-access')) {
            if (!empty($request->from_date)) {
                $this->model->setFromDate($request->from_date);
            }
            if (!empty($request->to_date)) {
                $this->model->setToDate($request->to_date);
            }
            $this->set_datatable_default_properties($request); //set datatable default properties
            $list = $this->model->getDatatableList(); //get table data
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('expense-details')) {
                    $action .= ' <a class="dropdown-item view_data" href="' . route("expense.show", $value->voucherNo) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('expense-edit') and $value->status != 1) {
                    $action .= ' <a class="dropdown-item" href="' . route("expense.edit", $value->voucherNo) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('expense-status-change') and $value->status != 1) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->voucherNo . '" data-name="' . $value->voucherNo . '" data-status="' . $value->status . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('expense-delete') and $value->status != 1) {
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
        if (permission('expense-add')) {
            $setTitle    = __('file.Expense');
            $setSubTitle = __('file.Expense');
            $this->setPageData($setSubTitle, $setSubTitle, 'far fa-money-bill-alt', [['name' => $setTitle], ['name' => $setSubTitle]]);
            $data = [
                'voucher_no'  => self::expense . '-' . round(microtime(true) * 1000),
                'expenseItems'   => ExpenseItem::OtherExpense()->get()
            ];
            return view('account::expense.create', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function store(ExpenseFormRequest $request)
    {
        if ($request->ajax() && permission('expense-add')) {
            DB::beginTransaction();
            try {
                $expense = ChartOfHead::firstWhere(['expense_item_id' => $request->expense_item_id]);
                $this->balanceDebit($expense->id, $request->date, $request->voucher_no, $request->narration, $request->amount);
                $this->balanceCredit($request->account_id, $request->date, $request->voucher_no, $request->narration, $request->amount);
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
        if (permission('expense-details')) {
            $this->setPageData('Expense', 'Payment', 'far fa-money-bill-alt', [['name' => 'Party'], ['name' => 'Payment']]);
            $data = $this->model->with('coh')->where(['voucher_no' => $voucher_no])->get();
            return view('account::expense.show', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }
    public function edit($voucher_no)
    {
        if (permission('expense-edit')) {
            $this->setPageData('Expense', 'Payment', 'far fa-money-bill-alt', [['name' => 'Expense'], ['name' => 'Payment']]);
            foreach ($this->model->with('coh')->where(['voucher_no' => $voucher_no])->get() as $value) {
                $voucherNo = $value->voucher_no;
                $date      = $value->date;
                if ($value->debit > 0) {
                    $expenseItemId = $value->coh->expense_item_id;
                    $amount        = $value->debit;
                }
                if ($value->credit > 0) {
                    $paymentMethodCheck = ChartOfHead::findOrFail($value->chart_of_head_id);
                    if ($paymentMethodCheck->classification == 1) {
                        $paymentMethod = 1;
                    } elseif ($paymentMethodCheck->classification == 5) {
                        $paymentMethod = 2;
                    } else {
                        $paymentMethod = 3;
                    }
                    $account = $value->chart_of_head_id;
                }
                $narration  = $value->narration;
            }
            $data = [
                'voucherNo'     => $voucherNo,
                'date'          => $date,
                'expenseItemId' => $expenseItemId,
                'paymentMethod' => $paymentMethod,
                'account'       => $account,
                'amount'        => $amount,
                'narration'     => $narration,
                'expenseItems'   => ExpenseItem::where(['expense_type' => 2])->get()
            ];
            return view('account::expense.edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(ExpenseFormRequest $request)
    {
        if ($request->ajax() && permission('expense-edit')) {
            DB::beginTransaction();
            try {
                $voucher = $this->model->where(['voucher_no' => $request->update_id])->get();
                abort_if($voucher[0]->status == 1, 404);
                $voucher->each->delete();
                $expense = ChartOfHead::firstWhere(['expense_item_id' => $request->expense_item_id]);
                $this->balanceDebit($expense->id, $request->date, $request->voucher_no, $request->narration, $request->amount);
                $this->balanceCredit($request->account_id, $request->date, $request->voucher_no, $request->narration, $request->amount);
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
        if ($request->ajax() && permission('expense-status-change')) {
            DB::beginTransaction();
            try {
                $voucher = $this->model->where(['voucher_no' => $request->id])->get();
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
        if ($request->ajax() && permission('expense-delete')) {
            DB::beginTransaction();
            try {
                $voucher = $this->model->where(['voucher_no' => $request->id])->get();
                abort_if($voucher[0]->status == 1, 404);
                $voucher->each->delete();
                $output  = ['status' => 'success', 'message' => 'Data Deleted Successfully'];
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
    public function balanceDebit(int $expenseId, $date, $voucherNo, $narration, float $amount): void
    {
        Transaction::create([
            'chart_of_head_id' => $expenseId,
            'date'             => $date,
            'voucher_no'       => $voucherNo,
            'voucher_type'     => self::expense,
            'narration'        => $narration,
            'debit'            => $amount,
            'credit'           => 0,
            'status'           => 3,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name
        ]);
    }
    public function balanceCredit(int $accountId, $date, $voucherNo, $narration, float $amount): void
    {
        Transaction::create([
            'chart_of_head_id' => $accountId,
            'date'             => $date,
            'voucher_no'       => $voucherNo,
            'voucher_type'     => self::expense,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $amount,
            'status'           => 3,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name
        ]);
    }
}
