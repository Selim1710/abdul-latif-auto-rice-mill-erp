<?php

namespace Modules\Purchase\Http\Controllers;

use App\Models\Unit;
use Exception;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\Transaction;
use Modules\Category\Entities\Category;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;
use Modules\Purchase\Entities\Purchase;
use Illuminate\Support\Str;
use Modules\LaborHead\Entities\LaborBillRate;
use Modules\LaborHead\Entities\LaborBillRateDetail;
use Modules\LaborHead\Entities\LaborHead;
use Modules\Purchase\Entities\PurchaseProduct;
use Modules\Purchase\Entities\PurchaseProductReceive;
use Modules\Purchase\Entities\PurchaseProductReturn;
use Modules\Purchase\Http\Requests\PurchaseFormRequest;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;

class PurchaseController extends BaseController
{
    use UploadAble;
    private const purchase = 'PURCHASE';
    private const receive  = 'RECEIVE';
    private const return   = 'RETURN';
    public function __construct(Purchase $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('purchase-access')) {
            $setTitle = __('file.Purchase Manage');
            $this->setPageData($setTitle, $setTitle, 'fas fa-cart-arrow-down', [['name' => $setTitle]]);
            $data = [
                'parties'  => Party::all(),
            ];
            return view('purchase::index', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('purchase-access')) {
            if (!empty($request->invoice_no)) {
                $this->model->setInvoiceNo($request->invoice_no);
            }
            if (!empty($request->party_id)) {
                $this->model->setPartyId($request->party_id);
            }
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
                if (permission('purchase-view')) {
                    $action .= ' <a class="dropdown-item" href="' . route("purchase.details", $value->id) . '">' . $this->actionButton('Purchase Details') . '</a>';
                }
                if (permission('purchase-status-change') and $value->purchase_status != 4) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '" data-status="' . $value->purchase_status . '">' . $this->actionButton('Change Status') . '</a>';
                }
                if (permission('purchase-edit') and $value->purchase_status != 4) {
                    $action .= ' <a class="dropdown-item" href="' . route("purchase.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('purchase-receive') and $value->purchase_status == 4 and $value->total_receive_qty < $value->total_purchase_qty) {
                    $action .= ' <a class="dropdown-item" href="' . route("purchase.receive", $value->id) . '">' . $this->actionButton('Receive') . '</a>';
                }
                if (permission('purchase-receive-details') and isset($value->purchaseProductReceiveInvoiceList) and $value->purchase_status == 4) {
                    foreach ($value->purchaseProductReceiveInvoiceList as $receive) {
                        $action .= ' <a class="dropdown-item" href="' . route("purchase.receive.details", $receive->invoice_no) . '">' . $this->actionButton('Received Invoice') . '(' . $receive->date . ')' . '</a>' . ' ' . ' <a class="dropdown-item" href="' . route("purchase.receive.gate.pass", $receive->invoice_no) . '">' . $this->actionButton('Purchase Gate Pass') . '(' . $receive->date . ')' . '</a>';
                    }
                }
                if (permission('purchase-return') and $value->purchase_status == 4 and $value->total_receive_qty > 0 and $value->total_return_qty < $value->total_receive_qty) {
                    $action .= ' <a class="dropdown-item" href="' . route("purchase.return", $value->id) . '">' . $this->actionButton('Return') . '</a>';
                }
                if (permission('purchase-return-details') and isset($value->purchaseProductReturnInvoiceList) and $value->purchase_status == 4) {
                    foreach ($value->purchaseProductReturnInvoiceList as $return) {
                        $action .= ' <a class="dropdown-item" href="' . route("purchase.return.details", $return->invoice_no) . '">' . $this->actionButton('Return Invoice') . '(' . $return->date . ')' . '</a>' . ' ' . ' <a class="dropdown-item" href="' . route("purchase.return.gate.pass", $return->invoice_no) . '">' . $this->actionButton('Return Gate Pass') . '(' . $return->date . ')' . '</a>';
                    }
                }
                if (permission('purchase-delete') and $value->purchase_status != 4) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->invoice_no;
                $row[]  = $value->purchase_date;
                $row[]  = PARTY_TYPE_LABEL[$value->party_type];
                $row[]  = $value->party_type == 1 ? $value->party->name : $value->party_name;
                $row[]  = PURCHASE_STATUS_LABEL[$value->purchase_status];
                $row[]  = $value->total_purchase_qty;
                $row[]  = $value->total_receive_qty;
                $row[]  = $value->total_return_qty;
                $row[]  = number_format($value->total_purchase_sub_total, 2);
                $row[]  = number_format($value->total_receive_sub_total, 2);
                $row[]  = number_format($value->total_return_sub_total, 2);
                $row[]  = $value->created_by;
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
        if (permission('purchase-add')) {
            $setTitle = __('file.Purchase Add');
            $this->setPageData($setTitle, $setTitle, 'fas fa-cart-arrow-down', [['name' => $setTitle]]);
            $data = [
                'invoice_no'   => self::purchase . '-' . round(microtime(true) * 1000),
                'parties'      => Party::latest()->get(),
                'warehouses'   => Warehouse::with('labour_load_unload_head')->get(),
                'categories'   => Category::latest()->get()
            ];
            // return $data['warehouses'];

            return view('purchase::create', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function store(PurchaseFormRequest $request)
    {
        if ($request->ajax() && permission('purchase-add')) {
            DB::beginTransaction();
            try {
                $purchase    = [];
                $collection  = collect($request->all())->except('_token', 'purchase')->merge(['created_by' => auth()->user()->name]);
                $result      = $this->model->with('purchaseProduct')->create($collection->all());
                if ($request->has('purchase')) {
                    foreach ($request->purchase as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['rec_qty'])) {
                            $purchase[]  = [
                                'warehouse_id' => $value['warehouse_id'],
                                'load_unload_rate' => $value['load_unload_rate'],
                                'product_id'   => $value['product_id'],
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'rec_qty'      => $value['rec_qty'],
                                'price'        => $value['price'],
                                'sub_total'    => $value['sub_total'],
                                'receive_scale' => 0,
                                'receive_qty'  => 0,
                                'return_scale' => 0,
                                'return_qty'   => 0,
                                'purchase_date' => $request->purchase_date,
                                'note'         => $value['note'],
                            ];
                        }
                    }
                }
                $result->purchaseProduct()->attach($purchase);
                $this->model->flushCache();
                if ($request->account_id && $request->purchase_status == 4) {
                    if ($request->party_type == 1) {
                        $party       = ChartOfHead::firstWhere(['master_head' => 3, 'party_id' => $request->party_id]);
                        $cohId       = $party->id;
                        $name        = $party->name;
                    } else {
                        $cohId       = 28;
                        $name        = 'Walking Party';
                    }
                    $narration = $name . ' purchase paid amount ' . $request->paid_amount . ' invoice no -' . $request->invoice_no;
                    $this->balanceCredit($request->account_id, $request->invoice_no, $narration, $request->purchase_date, abs($request->paid_amount));
                    $this->balanceCredit($cohId, $request->invoice_no, $narration, $request->purchase_date, abs($request->paid_amount));
                }
                $output = ['status' => 'success', 'message' => 'Data Store Successfully'];
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
    public function purchaseDetails($id)
    {
        if (permission('purchase-view')) {
            $setTitle = __('file.Purchase Details');
            $this->setPageData($setTitle, $setTitle, 'fab fa-opencart', [['name' => $setTitle]]);
            $purchase = $this->model->with('party', 'purchaseProductList', 'purchaseProductList.warehouse', 'purchaseProductList.product', 'purchaseProductList.product.unit')->findOrFail($id);
            return view('purchase::purchaseDetails', compact('purchase'));
        }
    }
    public function edit($id)
    {
        if (permission('purchase-edit')) {
            $setTitle = __('file.Purchase Edit');
            $this->setPageData($setTitle, $setTitle, 'fab fa-opencart', [['name' => $setTitle]]);
            $edit = $this->model->with('party', 'purchaseProductList', 'purchaseProductList.product', 'purchaseProductList.product.category', 'purchaseProductList.product.unit')->findOrFail($id);
            abort_if($edit->purchase_status == 4, 404);
            $data = [
                'edit'         => $edit,
                'parties'      => Party::all(),
                'warehouses'   => Warehouse::all(),
                'categories'   => Category::all(),
                'units'        => Unit::where([['base_unit', '!=', null]])->get(),
            ];
            return view('purchase::edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(PurchaseFormRequest $request)
    {
        if ($request->ajax() && permission('purchase-edit')) {
            DB::beginTransaction();
            try {
                $purchase   = [];
                $collection = collect($request->all())->except('_token', 'purchase')->merge(['modified_by' => auth()->user()->name]);
                $data       = $this->model->findOrFail($request->update_id);
                abort_if($data->purchase_status == 4, 404);
                if ($request->has('purchase')) {
                    foreach ($request->purchase as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['qty']) && !empty($value['rec_qty'])) {
                            $purchase[Str::random(5)]  = [
                                'warehouse_id' => $value['warehouse_id'],
                                'load_unload_rate' => $value['load_unload_rate'],
                                'product_id'   => $value['product_id'],
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'rec_qty'      => $value['rec_qty'],
                                'price'        => $value['price'],
                                'sub_total'    => $value['sub_total'],
                                'receive_scale' => 0,
                                'receive_qty'  => 0,
                                'return_scale' => 0,
                                'return_qty'   => 0,
                                'purchase_date' => $request->purchase_date,
                                'note'         => $value['note'],
                            ];
                        }
                    }
                }
                $data->update($collection->all());
                $data->purchaseProduct()->sync($purchase);
                $data->touch();
                $this->model->flushCache();
                if ($request->account_id && $request->purchase_status == 4) {
                    if ($request->party_type == 1) {
                        $party       = ChartOfHead::firstWhere(['master_head' => 3, 'party_id' => $request->party_id]);
                        $cohId       = $party->id;
                        $name        = $party->name;
                    } else {
                        $cohId       = 28;
                        $name        = 'Walking Customer';
                    }
                    $narration = $name . ' purchase paid amount ' . $request->paid_amount . ' invoice no -' . $request->invoice_no;
                    $this->balanceCredit($request->account_id, $request->invoice_no, $narration, $request->purchase_date, abs($request->paid_amount));
                    $this->balanceCredit($cohId, $request->invoice_no, $narration, $request->purchase_date, abs($request->paid_amount));
                }
                $output = ['status' => 'success', 'message' => 'Data Updated Successfully'];
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
        // return $request;

        if ($request->ajax() && permission('purchase-status-change')) {
            DB::beginTransaction();
            try {
                $purchase = $this->model->with('purchaseProductList')->findOrFail($request->purchase_id);

                if (!empty($purchase->purchaseProductList) && $request->purchase_status == 4) {
                    // labour-bill-generate
                    foreach ($purchase->purchaseProductList as $key => $purchase_product) {
                        $labor_head = LaborHead::find(1); // load-unload
                        $amount  = ($purchase_product->qty ?? 0) * ($purchase_product->load_unload_rate ?? 0);
                        $coh     = ChartOfHead::firstWhere(['labor_head_id' => $labor_head->id]);
                        $note = "Purchase";
                        $this->labour_head_Credit($coh->id, $purchase_product->id, $note, $amount);
                    }
                }

                abort_if($purchase->purchase_status == 4, 404);
                $purchase->update(['purchase_status' => $request->purchase_status]);
                $this->model->flushCache();
                if (!empty($purchase->account_id) && $request->purchase_status == 4) {
                    if ($purchase->party_type == 1) {
                        $party       = ChartOfHead::firstWhere(['master_head' => 3, 'party_id' => $purchase->party_id]);
                        $cohId       = $party->id;
                        $name        = $party->name;
                    } else {
                        $cohId       = 28;
                        $name        = 'Walking Customer';
                    }
                    $narration = $name . ' purchase paid amount ' . $request->paid_amount . ' invoice no -' . $request->invoice_no;
                    $this->balanceCredit($purchase->account_id, $purchase->invoice_no, $narration, $purchase->purchase_date, abs($purchase->paid_amount));
                    $this->balanceCredit($cohId, $purchase->invoice_no, $narration, $purchase->purchase_date, abs($purchase->paid_amount));
                }
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

    public function labour_head_Credit($cohId, $invoiceNo, $narration, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => date('Y-m-d'),
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => "LABOR-BILL",
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $paidAmount,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }

    public function delete(Request $request)
    {
        if ($request->ajax() && permission('purchase-delete')) {
            DB::beginTransaction();
            try {
                $purchase = $this->model->with('purchaseProductList')->findOrFail($request->id);
                abort_if($purchase->purchase_status == 4, 404);
                $purchase->purchaseProductList()->delete();
                $purchase->delete();
                $this->model->flushCache();
                $output   = ['status' => 'success', 'message' => 'Data Deleted Successfully'];
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
    public function receive($id)
    {
        if (permission('purchase-receive')) {
            $setTitle    = __('file.Purchase');
            $setSubTitle = __('file.Receive');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-edit', [['name' => $setTitle, 'link' => route('purchase')], ['name' => $setSubTitle]]);
            $purchase    = $this->model->with('party', 'purchaseProductList', 'purchaseProductList.warehouse', 'purchaseProductList.product', 'purchaseProductList.product.unit')->findOrFail($id);
            abort_if($purchase->purchase_status != 4 || ($purchase->total_receive_qty > $purchase->total_purchase_qty), 404);
            $invoiceNo   = self::receive . '-' . round(microtime(true) * 1000);
            return view('purchase::purchaseReceive', compact('purchase', 'invoiceNo'));
        } else {
            return $this->access_blocked();
        }
    }
    public function receiveStore(Request $request)
    {
        if ($request->ajax() && permission('purchase-receive')) {
            DB::beginTransaction();
            try {
                $purchaseProductReceive = [];
                $purchase               = $this->model->findOrFail($request->purchase_id);
                abort_if($purchase->purchase_status != 4 || ($purchase->total_receive_qty > $purchase->total_purchase_qty), 404);
                if ($request->has('receive')) {
                    foreach ($request->receive as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['receive_qty'])) {
                            $scale = $value['receive_qty'] * $value['scale'] / $value['purchase_qty'];
                            $purchaseProductReceive[]   = [
                                'invoice_no'     =>  $request->invoice_no,
                                'warehouse_id'   =>  $value['warehouse_id'],
                                'product_id'     =>  $value['product_id'],
                                'price'          =>  $value['price'],
                                'scale'          =>  $scale,
                                'qty'            =>  $value['receive_qty'],
                                'date'           =>  $request->receive_date
                            ];
                            $purchaseProduct  = PurchaseProduct::findOrFail($value['id']);
                            if (($purchaseProduct->receive_qty + $value['receive_qty'] > $purchaseProduct->rec_qty) || ($purchaseProduct->receive_scale + $scale > $purchaseProduct->scale)) {
                                return response()->json(['status' => 'error', 'message' => 'Receive Quantity Not Be Greater Then Purchase Quantity']);
                            }
                            $purchaseProduct->update([
                                'receive_scale'  => $purchaseProduct->receive_scale + $scale,
                                'receive_qty'    => $purchaseProduct->receive_qty + $value['receive_qty']
                            ]);
                            $warehouseProduct = WarehouseProduct::firstOrNew([
                                'warehouse_id'   => $value['warehouse_id'],
                                'product_id'     => $value['product_id']
                            ], [
                                'scale'          => $scale,
                                'qty'            => $value['receive_qty']
                            ]);
                            if (!empty($warehouseProduct)) {
                                $warehouseProduct->update([
                                    'scale'      => $warehouseProduct->scale + $scale,
                                    'qty'        => $warehouseProduct->qty   + $value['receive_qty']
                                ]);
                            }
                            $warehouseProduct->save();
                        }
                    }
                }
                $this->model->flushCache();
                if ($purchase->party_type == 1) {
                    $party       = ChartOfHead::firstWhere(['master_head' => 3, 'party_id' => $purchase->party_id]);
                    $cohId       = $party->id;
                    $name        = $party->name;
                } else {
                    $cohId       = 28;
                    $name        = 'Walking Customer';
                }
                $narration       = $name . ' purchase receive product amount ' . $request->total_receive_sub_total . ' invoice no -' . $purchase->invoice_no;
                $this->balanceDebit(18, $purchase->invoice_no, $narration, $purchase->purchase_date, $request->total_receive_sub_total);
                if ($purchase->payment_status == 3) {
                    $this->balanceCredit($cohId, $purchase->invoice_no, $narration, $purchase->purchase_date, $request->total_receive_sub_total);
                }
                if ($purchase->payment_status == 2 && $purchase->total_receive_sub_total + $request->total_receive_sub_total > $purchase->paid_amount) {
                    $this->balanceCredit($cohId, $purchase->invoice_no, $narration, $purchase->purchase_date, $purchase->total_receive_sub_total + $request->total_receive_sub_total - $purchase->paid_amount);
                }
                $purchase->update([
                    'total_receive_qty'        => $purchase->total_receive_qty + $request->total_receive_qty,
                    'total_receive_sub_total'  => $purchase->total_receive_sub_total + $request->total_receive_sub_total
                ]);
                $purchase->purchaseProductReceive()->attach($purchaseProductReceive);
                $output = ['status' => 'success', 'message' => 'Data Store Successfully'];
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
    public function receiveDetails($invoiceNo)
    {
        if (permission('purchase-receive-details')) {
            $setTitle    = __('file.Receive');
            $setSubTitle = __('file.Receive Details');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('purchase')], ['name' => $setSubTitle]]);
            $purchaseProductReceive = PurchaseProductReceive::with('purchase', 'purchase.party', 'warehouse', 'product', 'product.unit')->where(['invoice_no' => $invoiceNo])->get();
            return view('purchase::purchaseReceiveDetails', compact('purchaseProductReceive'));
        } else {
            return $this->access_blocked();
        }
    }
    public function receiveGatePass($invoiceNo)
    {
        if (permission('purchase-receive-details')) {
            $setTitle    = __('file.Receive');
            $setSubTitle = __('file.Gate Pass');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('purchase')], ['name' => $setSubTitle]]);
            $purchaseProductReceive = PurchaseProductReceive::with('purchase', 'purchase.party', 'warehouse', 'product', 'product.unit')->where(['invoice_no' => $invoiceNo])->get();
            return view('purchase::purchaseProductReceiveGatePass', compact('purchaseProductReceive'));
        } else {
            return $this->access_blocked();
        }
    }
    public function return($id)
    {
        if (permission('purchase-return')) {
            $setTitle    = __('file.Purchase');
            $setSubTitle = __('file.Return');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-edit', [['name' => $setTitle, 'link' => route('purchase')], ['name' => $setSubTitle]]);
            $purchase    = $this->model->with('party', 'purchaseProductList', 'purchaseProductList.warehouse', 'purchaseProductList.product', 'purchaseProductList.product.unit')->findOrFail($id);
            abort_if($purchase->purchase_status != 4 || ($purchase->total_return_qty > $purchase->total_receive_qty) || ($purchase->total_receive_qty == 0), 404);
            $invoiceNo   = self::return . '-' . round(microtime(true) * 1000);
            return view('purchase::purchaseReturn', compact('purchase', 'invoiceNo'));
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function returnStore(Request $request)
    {
        if ($request->ajax() && permission('purchase-return')) {
            DB::beginTransaction();
            try {
                $purchaseProductReturn   = [];
                $purchase                = $this->model->findOrFail($request->purchase_id);
                abort_if($purchase->purchase_status != 4 || ($purchase->total_return_qty > $purchase->total_receive_qty) || ($purchase->total_receive_qty == 0), 404);
                if ($request->has('return')) {
                    foreach ($request->return as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['return_qty'])) {
                            $scale = $value['return_qty'] * $value['scale'] / $value['rec_qty'];
                            $purchaseProductReturn[]   = [
                                'invoice_no'     =>  $request->invoice_no,
                                'warehouse_id'   =>  $value['warehouse_id'],
                                'product_id'     =>  $value['product_id'],
                                'price'          =>  $value['price'],
                                'scale'          =>  $scale,
                                'qty'            =>  $value['return_qty'],
                                'date'           =>  $request->return_date
                            ];
                            $warehouseProduct = WarehouseProduct::firstWhere(['warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                            $purchaseProduct  = PurchaseProduct::findOrFail($value['id']);
                            if (($purchaseProduct->return_qty + $value['return_qty'] > $purchaseProduct->receive_qty) || ($purchaseProduct->return_scale + $scale > $purchaseProduct->receive_scale)) {
                                return response()->json(['status' => 'error', 'message' => 'Return Quantity Not Be Greater Then Receive Quantity']);
                            }
                            if (($value['return_qty'] > $warehouseProduct->qty) || ($scale > $warehouseProduct->scale)) {
                                return response()->json(['status' => 'error', 'message' => 'Return Quantity Not Be Greater Then Stock Quantity']);
                            }
                            $purchaseProduct->update([
                                'return_scale' => $purchaseProduct->return_scale + $scale,
                                'return_qty'  => $purchaseProduct->return_qty + $value['return_qty']
                            ]);
                            $warehouseProduct->update([
                                'scale' => $warehouseProduct->scale - $scale,
                                'qty'   => $warehouseProduct->qty - $value['return_qty']
                            ]);
                        }
                    }
                }
                $purchase->update([
                    'total_return_qty'        => $purchase->total_return_qty + $request->total_return_qty,
                    'total_return_sub_total'  => $purchase->total_return_sub_total + $request->total_return_sub_total
                ]);
                $purchase->purchaseProductReturn()->attach($purchaseProductReturn);
                $this->model->flushCache();
                if ($purchase->party_type == 1) {
                    $party       = ChartOfHead::firstWhere(['master_head' => 3, 'party_id' => $purchase->party_id]);
                    $cohId       = $party->id;
                    $name        = $party->name;
                } else {
                    $cohId       = 28;
                    $name        = 'Walking Customer';
                }
                $narration       = $name . ' purchase return product amount ' . $request->total_return_sub_total . ' invoice no -' . $purchase->invoice_no;
                $this->balanceCredit(18, $purchase->invoice_no, $narration, $purchase->purchase_date, $request->total_return_sub_total);
                $this->balanceDebit($cohId, $purchase->invoice_no, $narration, $purchase->purchase_date, $request->total_return_sub_total);
                $output = ['status' => 'success', 'message' => 'Data Store Successfully'];
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
    public function returnDetails($invoiceNo)
    {
        if (permission('purchase-return-details')) {
            $setTitle    = __('file.Return');
            $setSubTitle = __('file.Return Details');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('purchase')], ['name' => $setSubTitle]]);
            $purchaseProductReturn = PurchaseProductReturn::with('purchase', 'purchase.party', 'warehouse', 'product', 'product.unit')->where(['invoice_no' => $invoiceNo])->get();
            return view('purchase::purchaseReturnDetails', compact('purchaseProductReturn'));
        } else {
            return $this->access_blocked();
        }
    }
    public function returnGatePass($invoiceNo)
    {
        if (permission('purchase-return-details')) {
            $setTitle    = __('file.Return');
            $setSubTitle = __('file.Gate Pass');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('purchase')], ['name' => $setSubTitle]]);
            $purchaseProductReturn = PurchaseProductReturn::with('purchase', 'purchase.party', 'warehouse', 'product', 'product.unit')->where(['invoice_no' => $invoiceNo])->get();
            return view('purchase::purchaseProductReturnGatePass', compact('purchaseProductReturn'));
        } else {
            return $this->access_blocked();
        }
    }
    public function purchaseCategoryProduct($categoryId)
    {
        return Product::where(['category_id' => $categoryId])->get();
    }
    public function purchaseProductDetails($productId)
    {
        return Product::with('unit')->findOrFail($productId);
    }
    public function balanceDebit($cohId, $invoiceNo, $narration, $date, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => $date,
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => self::purchase,
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
            'voucher_type'     => self::purchase,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $paidAmount,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }
}
