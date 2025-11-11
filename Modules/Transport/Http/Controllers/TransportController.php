<?php

namespace Modules\Transport\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\TransportService;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Expense\Entities\Expense;
use Modules\Expense\Entities\ExpenseItem;
use Modules\Party\Entities\Party;
use Modules\Transport\Entities\Transport;
use Illuminate\Support\Facades\Notification;
use Modules\Transport\Entities\Driver;
use Modules\Transport\Entities\ExpenseCategory;
use Modules\Transport\Entities\TransportDetail;
use Modules\Transport\Entities\Truck;
use Modules\Transport\Http\Requests\TransportRequestForm;

class TransportController extends BaseController
{
    private const t = 'TRANSPORT';
    public function __construct(Transport $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('transport-access')) {
            $setTitle = __('file.Transport Manage');
            $this->setPageData($setTitle, $setTitle, 'fas fa-list-alt', [['name' => $setTitle]]);
            $data = [
                'trucks'  => Truck::all()
            ];
            return view('transport::transport.index', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if ($request->ajax() && permission('transport-access')) {
            if (!empty($request->voucher_no)) {
                $this->model->setVoucherNo($request->voucher_no);
            }
            if (!empty($request->truck_id)) {
                $this->model->setTruckID($request->truck_id);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('transport-edit')) {
                    $action .= ' <a class="dropdown-item" href="' . route("transport.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('transport-view')) {
                    $action .= ' <a class="dropdown-item view_data" href="' . route("transport.view", $value->id) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('transport-change-status') && $value->status != 1) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '" data-status="' . $value->status . '">' . $this->actionButton('Change Status') . '</a>';
                }
                if (permission('transport-delete')) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->date;
                $row[]  = $value->invoice_no;
                $row[]  = PARTY_TYPE_LABEL[$value->party_type];
                $row[]  = $value->party_type == 1 ? $value->party->name : $value->party_name;
                $row[]  = $value->truck->truck_no;
                $row[]  = $value->driver_name;
                $row[]  = $value->rent_name;
                $row[]  = number_format($value->rent_amount, 2);
                $row[]  = number_format($value->total_expense, 2);
                $row[]  = number_format($value->income, 2);
                $row[]  = VOUCHER_APPROVE_STATUS_LABEL[$value->status];
                $row[]  = $value->created_by;
                $row[]  = action_button($action); //custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function create()
    {
        if (permission('transport-access')) {
            $setTitle    = __('file.Transports');
            $setSubTitle = __('file.Transports Add');
            $this->setPageData($setSubTitle, $setSubTitle, 'far fa-money-bill-alt', [['name' => $setTitle], ['name' => $setSubTitle]]);
            $data = [
                'parties'        => Party::all(),
                'invoice_no'     => self::t . '-' . round(microtime(true) * 1000),
                'trucks'         => Truck::allTrucks(),
                'expenseItems'   => ExpenseItem::TransportExpense()->get()
            ];
            return view('transport::transport.create', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function store(TransportRequestForm $request)
    {
        if ($request->ajax() && permission('transport-add')) {
            DB::beginTransaction();
            try {
                $transport  = [];
                $collection = collect($request->all())->except('_token', 'transport')->merge(['status' => 3, 'created_by' => auth()->user()->name]);
                $result     = $this->model->create($collection->all());
                if ($request->has('transport')) {
                    foreach ($request->transport as $value) {
                        if (!empty($value['expense_item_id']) && !empty($value['amount'])) {
                            $transport[] = [
                                'expense_item_id' => $value['expense_item_id'],
                                'amount'          => $value['amount']
                            ];
                        }
                    }
                }
                $result->transportDetails()->attach($transport);
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
    public function show(int $id)
    {
        if (permission('transport-view')) {
            $setTitle    = __('file.Transport');
            $setSubTitle = __('file.Transport Details');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('transport')], ['name' => $setTitle]]);
            $data = [
                'details' => $this->model->with('truck', 'party', 'transportDetailsList.expenseItem')->findOrFail($id),
            ];
            return view('transport::transport.details', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function edit(int $id)
    {
        if (permission('transport-edit')) {
            $setTitle    = __('file.Transport');
            $setSubTitle = __('file.Transport Details');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('transport')], ['name' => $setTitle]]);
            $data = [
                'details'        => $this->model->with('truck', 'party', 'transportDetailsList')->findOrFail($id),
                'parties'        => Party::all(),
                'trucks'         => Truck::allTrucks(),
                'expenseItems'   => ExpenseItem::OtherExpense()->get()
            ];
            return view('transport::transport.edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(TransportRequestForm $request)
    {
        if ($request->ajax() && permission('transport-edit')) {
            DB::beginTransaction();
            try {
                $transport  = [];
                $collection = collect($request->all())->except('_token', 'transport')->merge(['modified' => auth()->user()->name]);
                $result     = $this->model->findOrFail($request->update_id);
                if ($request->has('transport')) {
                    foreach ($request->transport as $value) {
                        if (!empty($value['expense_item_id']) && !empty($value['amount'])) {
                            $transport[Str::random(5)] = [
                                'expense_item_id' => $value['expense_item_id'],
                                'amount'          => $value['amount']
                            ];
                        }
                    }
                }
                $result->update($collection->all());
                $result->transportDetails()->sync($transport);
                $result->touch();
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
    public function changeStatus(Request $request)
    {
        if ($request->ajax() && permission('transport-change-status')) {
            DB::beginTransaction();
            try {
                $transport      = $this->model->with('transportDetailsList')->findOrFail($request->id);
                $transportCohId = ChartOfHead::firstWhere(['truck_id' => $transport->truck_id]);
                abort_if($transport->status == 1, 404);
                if ($transport->party_type == 1) {
                    $party      = ChartOfHead::firstWhere(['master_head' => 1, 'party_id' => $transport->party_id]);
                    $cohId      = $party->id;
                    $name       = $party->name;
                } else {
                    $cohId      = 22;
                    $name       = 'Walking Customer';
                }
                foreach ($transport->transportDetailsList as $value) {
                    $expenseItemCohId = ChartOfHead::firstWhere(['master_head' => 7, 'expense_item_id' => $value->expense_item_id]);
                    $narration        = 'Transport Expense Cost Invoice No -' . $transport->invoice_no;
                    $this->balanceDebit($expenseItemCohId->id, $transport->invoice_no, $narration, $transport->date, $value->amount);
                }
                $transport->update([
                    'status'     => 1
                ]);
                $partyNarration  = $name . ' Transport Location ' . $transport->rent_name . ' Receivable Amount ' . $transport->rent_amount;
                $this->balanceDebit($cohId, $transport->invoice_no, $partyNarration, $transport->date, $transport->rent_amount);
                $this->balanceDebit($transportCohId->id, $transport->invoice_no, $partyNarration, $transport->date, $transport->rent_amount);
                $this->balanceCredit(24, $transport->invoice_no, $partyNarration, $transport->date, $transport->total_expense);
                $output = ['status' => 'success', 'message' => 'Transport Status Change Successfully'];
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
        if ($request->ajax() && permission('transport-delete')) {
            DB::beginTransaction();
            try {
                $transport = $this->model->with('transportDetailsList')->findOrFail($request->id);
                abort_if($transport->status == 1, 404);
                $transport->transportDetailsList()->delete();
                $transport->delete();
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
    public function balanceDebit($cohId, $invoiceNo, $narration, $date, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => $date,
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => self::t,
            'narration'        => $narration,
            'debit'            => $paidAmount,
            'credit'           => 0,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }
    public function balanceCredit($cohId, $invoiceNo, $narration, $date, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => $date,
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => self::t,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $paidAmount,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }
}
