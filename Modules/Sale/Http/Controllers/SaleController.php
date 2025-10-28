<?php

namespace Modules\Sale\Http\Controllers;

use Exception;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Category\Entities\Category;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;
use Modules\Sale\Entities\Sale;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleProductDelivery;
use Modules\Sale\Entities\SaleProductReturn;
use Modules\Sale\Http\Requests\SaleFormRequest;
use Modules\Setting\Entities\Warehouse;
use App\Http\Controllers\BaseController;
use Modules\Account\Entities\Transaction;
use Modules\LaborHead\Entities\LaborHead;
use Modules\Stock\Entities\WarehouseProduct;

class SaleController extends BaseController
{
    use UploadAble;
    private const sale     = 'SALE';
    private const delivery = 'DELIVERY';
    private const return   = 'RETURN';
    public function __construct(Sale $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('sale-access')) {
            $setTitle = __('file.Sale Manage');
            $this->setPageData($setTitle, $setTitle, 'fab fa-opencart', [['name' => $setTitle]]);
            $data = [
                'parties'  => Party::all(),
            ];
            return view('sale::index', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('sale-access')) {
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
            if (!empty($request->sale_status)) {
                $this->model->setSaleStatus($request->sale_status);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('sale-view')) {
                    $action .= ' <a class="dropdown-item view_data" href="' . route("sale.show", $value->id) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('sale-edit') and $value->sale_status != 4) {
                    $action .= ' <a class="dropdown-item" href="' . route("sale.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('sale-approve') and $value->sale_status != 4) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '" data-status="' . $value->status . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('sale-delivery') and $value->sale_status == 4 and $value->total_delivery_qty < $value->total_sale_qty) {
                    $action .= ' <a class="dropdown-item" href="' . route("sale.delivery", $value->id) . '">' . $this->actionButton('Delivery') . '</a>';
                }
                if (permission('sale-delivery-invoice') and isset($value->saleProductDeliveryInvoiceList) and $value->sale_status == 4) {
                    foreach ($value->saleProductDeliveryInvoiceList as $delivery) {
                        $action .= ' <a class="dropdown-item" href="' . route("sale.delivery.invoice", $delivery->invoice_no) . '">' . $this->actionButton('Delivery Invoice') . '(' . $delivery->date . ')' . '</a>' . ' <a class="dropdown-item" href="' . route("sale.delivery.invoice.gate.pass", $delivery->invoice_no) . '">' . $this->actionButton('Sale Gate Pass') . '(' . $delivery->date . ')' . '</a>';
                    }
                }
                if (permission('sale-return') and $value->sale_status == 4 and $value->total_delivery_qty > 0 and $value->total_return_qty < $value->total_delivery_qty) {
                    $action .= ' <a class="dropdown-item" href="' . route("sale.return", $value->id) . '">' . $this->actionButton('Return') . '</a>';
                }
                if (permission('sale-return-invoice') and isset($value->saleProductReturnInvoiceList) and $value->sale_status == 4) {
                    foreach ($value->saleProductReturnInvoiceList as $return) {
                        $action .= ' <a class="dropdown-item" href="' . route("sale.return.invoice", $return->invoice_no) . '">' . $this->actionButton('Return Invoice') . '(' . $return->date . ')' . '</a>' . '<a class="dropdown-item" href="' . route("sale.return.invoice.gate.pass", $return->invoice_no) . '">' . $this->actionButton('Return Gate Pass') . '(' . $return->date . ')' . '</a>';
                    }
                }
                if (permission('sale-delete') and $value->sale_status != 4) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->invoice_no;
                $row[]  = $value->sale_date;
                $row[]  = PARTY_TYPE_LABEL[$value->party_type];
                $row[]  = $value->party_type == 1 ? $value->party->name : $value->party_name;
                $row[]  = SALE_TYPE_LABEL[$value->sale_type];
                $row[]  = SALE_STATUS_LABEL[$value->sale_status];
                $row[]  = $value->total_sale_qty;
                $row[]  = $value->total_delivery_qty;
                $row[]  = $value->total_return_qty;
                $row[]  = number_format($value->total_sale_sub_total, 2);
                $row[]  = number_format($value->total_delivery_sub_total, 2);
                $row[]  = number_format($value->total_return_sub_total, 2);
                $row[]  = $value->created_by;
                $row[]  = $value->modified_by ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
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
        if (permission('sale-add')) {
            $setTitle = __('file.Add Sale');
            $this->setPageData($setTitle, $setTitle, 'fas fa-shopping-cart', [['name' => $setTitle]]);
            $data = [
                'invoice_no'   => self::sale . '-' . round(microtime(true) * 1000),
                'parties'      => Party::all(),
                'warehouses'   => Warehouse::all(),
                'categories'   => Category::all(),
            ];
            return view('sale::create', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function store(SaleFormRequest $request)
    {
        if ($request->ajax() && permission('sale-add')) {
            DB::beginTransaction();
            try {
                $saleProduct = [];
                $collection  = collect($request->all())->except('_token', 'sale')->merge(['created_by' => auth()->user()->name]);
                $sale        = $this->model->create($collection->all());
                if ($request->has('sale')) {
                    foreach ($request->sale as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['qty']) && !empty($value['scale'])  && !empty($value['sel_qty']) && !empty($value['sub_total'])) {
                            $saleProduct[]    = [
                                'warehouse_id'           => $value['warehouse_id'],
                                'load_unload_rate'           => $value['load_unload_rate'],
                                'product_id'             => $value['product_id'],
                                'qty'                    => $value['qty'],
                                'scale'                  => $value['scale'],
                                'sel_qty'                => $value['sel_qty'],
                                'price'                  => $value['price'],
                                'sub_total'              => $value['sub_total'],
                                'delivery_scale'         => 0,
                                'delivery_qty'           => 0,
                                'return_scale'           => 0,
                                'return_qty'             => 0,
                                'date'                   => $request->sale_date,
                                'note'                   => $value['note']
                            ];
                        }
                    }
                }
                $sale->saleProduct()->attach($saleProduct);
                $this->model->flushCache();
                if ($request->account_id && $request->sale_status == 4) {
                    if ($request->party_type == 1) {
                        $party       = ChartOfHead::firstWhere(['master_head' => 1, 'party_id' => $request->party_id]);
                        $cohId       = $party->id;
                        $name        = $party->name;
                    } else {
                        $cohId       = 22;
                        $name        = 'Walking Party';
                    }
                    $narration = $name . ' sale receive amount ' . $request->paid_amount . ' invoice no -' . $request->invoice_no;
                    $this->balanceDebit($cohId, $request->invoice_no, $narration, $request->sale_date, abs($request->paid_amount));
                    $this->balanceDebit($request->account_id, $request->invoice_no, $narration, $request->sale_date, abs($request->paid_amount));
                }
                $output = ['status' => 'success', 'message' => 'Data Saved Successfully'];
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
        if (permission('sale-view')) {
            $setTitle    = __('file.Sale');
            $setSubTitle = __('file.Sale Details');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('sale')], ['name' => $setSubTitle]]);
            $sale = $this->model->with('party', 'saleProductList', 'saleProductList.warehouse', 'saleProductList.product.category', 'saleProductList.product', 'saleProductList.product.unit')->findOrFail($id);
            return view('sale::details', compact('sale'));
        } else {
            return $this->access_blocked();
        }
    }
    public function edit($id)
    {
        if (permission('sale-edit')) {
            $setTitle    = __('file.Sale');
            $setSubTitle = __('file.Edit Sale');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-edit', [['name' => $setTitle, 'link' => route('purchase')], ['name' => $setSubTitle]]);
            $edit = $this->model->with('party', 'saleProductList', 'saleProductList.warehouse', 'saleProductList.product.category', 'saleProductList.product', 'saleProductList.product.unit')->findOrFail($id);
            abort_if($edit->sale_status == 4, 404);
            $data = [
                'edit'         => $edit,
                'parties'      => Party::all(),
                'warehouses'   => Warehouse::all(),
                'categories'   => Category::all(),
            ];
            return view('sale::edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(SaleFormRequest $request)
    {
        if ($request->ajax() && permission('sale-edit')) {
            DB::beginTransaction();
            try {
                $saleProduct = [];
                $collection = collect($request->all())->except('_token', 'update_id', 'sale')->merge(['modified_by' =>  auth()->user()->name]);
                $sale       = $this->model->findOrFail($request->update_id);
                abort_if($sale->sale_status == 4, 4);
                if ($request->has('sale')) {
                    foreach ($request->sale as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['qty']) && !empty($value['scale'])  && !empty($value['sel_qty']) && !empty($value['sub_total'])) {
                            $saleProduct[Str::random(5)]    = [
                                'warehouse_id'           => $value['warehouse_id'],
                                'load_unload_rate'           => $value['load_unload_rate'],
                                'product_id'             => $value['product_id'],
                                'qty'                    => $value['qty'],
                                'scale'                  => $value['scale'],
                                'sel_qty'                => $value['sel_qty'],
                                'price'                  => $value['price'],
                                'sub_total'              => $value['sub_total'],
                                'delivery_scale'         => 0,
                                'delivery_qty'           => 0,
                                'return_scale'           => 0,
                                'return_qty'             => 0,
                                'date'                   => $request->sale_date,
                                'note'                   => $value['note']
                            ];
                        }
                    }
                }
                $sale->update($collection->all());
                $sale->saleProduct()->sync($saleProduct);
                $sale->touch();
                $this->model->flushCache();
                if ($request->account_id && $request->sale_status == 4) {
                    if ($request->party_type == 1) {
                        $party       = ChartOfHead::firstWhere(['master_head' => 1, 'party_id' => $request->party_id]);
                        $cohId       = $party->id;
                        $name        = $party->name;
                    } else {
                        $cohId       = 22;
                        $name        = 'Walking Customer';
                    }
                    $narration = $name . ' sale receive amount ' . $request->paid_amount . ' invoice no -' . $request->invoice_no;
                    $this->balanceDebit($cohId, $request->invoice_no, $narration, $request->sale_date, abs($request->paid_amount));
                    $this->balanceDebit($request->account_id, $request->invoice_no, $request->sale_date, $narration, abs($request->paid_amount));
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
        if ($request->ajax() && permission('sale-approve')) {
            DB::beginTransaction();
            try {
                $sale     = $this->model->findOrFail($request->sale_id);

                if (!empty($sale->saleProductList) && $request->sale_status == 4) {
                    // labour-bill-generate
                    foreach ($sale->saleProductList as $key => $sale_product) {
                        $labor_head = LaborHead::find(1); // load-unload
                        $amount  = ($sale_product->rec_qty ?? 0) * ($sale_product->load_unload_rate ?? 0);
                        $coh     = ChartOfHead::firstWhere(['labor_head_id' => $labor_head->id]);
                        $note = "Sale";
                        $this->labour_head_Credit($coh->id, $sale_product->id, $note, $amount);
                    }
                }

                $party    = ChartOfHead::firstWhere(['master_head' => 1, 'party_id' => $sale->party_id]);
                $sale->update(['sale_status' => $request->sale_status]);
                $this->model->flushCache();
                if (!empty($sale->account_id) && $request->sale_status == 4) {
                    if ($sale->party_type == 1) {
                        $party       = ChartOfHead::firstWhere(['master_head' => 1, 'party_id' => $sale->party_id]);
                        $cohId       = $party->id;
                        $name        = $party->name;
                    } else {
                        $cohId       = 22;
                        $name        = 'Walking Customer';
                    }
                    $narration = $name . ' sale receive amount ' . $request->paid_amount . ' invoice no -' . $request->invoice_no;
                    $this->balanceDebit($cohId, $sale->invoice_no, $narration, $sale->sale_date, abs($sale->paid_amount));
                    $this->balanceDebit($sale->account_id, $sale->invoice_no, $narration, $sale->sale_date, abs($sale->paid_amount));
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

    public function delivery($id)
    {
        if (permission('sale-delivery')) {
            $setTitle    = __('file.Sale');
            $setSubTitle = __('file.Delivery');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-edit', [['name' => $setTitle, 'link' => route('sale')], ['name' => $setSubTitle]]);
            $saleData    = $this->model->with('party', 'saleProductList', 'saleProductList.warehouse', 'saleProductList.product.category', 'saleProductList.product', 'saleProductList.product.unit')->findOrFail($id);
            $data = [
                'sale'       => $saleData,
                'invoiceNo'  => self::delivery . '-' . round(microtime(true) * 1000),
                'warehouses' => Warehouse::where('status', 1)->get(),
            ];
            return view('sale::delivery', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function deliveryStore(Request $request)
    {
        if ($request->ajax() && permission('sale-delivery')) {
            DB::beginTransaction();
            try {
                $saleProductDelivery     = [];
                $sale                    = $this->model->findOrFail($request->sale_id);
                if ($request->has('sale')) {
                    foreach ($request->sale as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['delivery_qty'] && !empty($value['sub_total']))) {
                            $scale = $value['delivery_qty'] * $value['scale'] / $value['qty'];
                            $saleProductDelivery[] = [
                                'sale_id'         =>  $request->sale_id,
                                'invoice_no'      =>  $request->invoice_no,
                                'warehouse_id'    =>  $value['warehouse_id'],
                                'product_id'      =>  $value['product_id'],
                                'price'           =>  $value['price'],
                                'scale'           =>  $scale,
                                'qty'             =>  $value['delivery_qty'],
                                'date'            =>  $request->delivery_date,
                            ];
                            $saleProduct       = SaleProduct::findOrFail($value['id']);
                            $warehouseProduct  = WarehouseProduct::firstWhere(['warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                            if (($saleProduct->delivery_qty + $value['delivery_qty'] > $saleProduct->sel_qty) || ($saleProduct->delivery_scale + $scale > $saleProduct->scale)) {
                                return response()->json(['status' => 'error', 'message' => 'Delivery Quantity Not Be Greater Then Sale Quantity']);
                            }
                            if ($value['delivery_qty'] > $warehouseProduct->qty || $scale > $warehouseProduct->scale) {
                                return response()->json(['status' => 'error', 'message' => 'Stock Or Scale Not Available In Warehouse']);
                            }
                            $saleProduct->update([
                                'delivery_scale'  => $saleProduct->delivery_scale + $scale,
                                'delivery_qty'    => $saleProduct->delivery_qty + $value['delivery_qty'],
                            ]);
                            $warehouseProduct->update([
                                'scale'           => $warehouseProduct->scale - $scale,
                                'qty'             => $warehouseProduct->qty - $value['delivery_qty'],
                            ]);
                        }
                    }
                }
                $this->model->flushCache();
                if ($sale->party_type == 1) {
                    $party       = ChartOfHead::firstWhere(['master_head' => 1, 'party_id' => $sale->party_id]);
                    $cohId       = $party->id;
                    $name        = $party->name;
                } else {
                    $cohId       = 22;
                    $name        = 'Walking Customer';
                }
                $narration       = $name . ' sale delivery product amount ' . $request->total_delivery_sub_total . ' invoice no -' . $sale->invoice_no;
                $this->balanceCredit(17, $sale->invoice_no, $narration, $sale->sale_date, $request->total_delivery_sub_total);
                if ($sale->payment_status == 3) {
                    $this->balanceDebit($cohId, $sale->invoice_no, $narration, $sale->sale_date, $request->total_delivery_sub_total);
                }
                if ($sale->payment_status == 2 && $sale->total_delivery_sub_total + $request->total_delivery_sub_total > $sale->paid_amount) {
                    $this->balanceDebit($cohId, $sale->invoice_no, $narration, $sale->sale_date, $sale->total_delivery_sub_total + $request->total_delivery_sub_total - $sale->paid_amount);
                }
                $sale->update([
                    'total_delivery_qty'        => $sale->total_delivery_qty + $request->total_delivery_qty,
                    'total_delivery_sub_total'  => $sale->total_delivery_sub_total + $request->total_delivery_sub_total
                ]);
                $sale->saleProductDelivery()->attach($saleProductDelivery);
                $output = ['status' => 'success', 'message' => 'Delivery Store Successfully'];
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
    public function deliveryInvoice($invoice_no)
    {
        if (permission('sale-delivery-invoice')) {
            $setTitle    = __('file.Sale');
            $setSubTitle = __('file.Delivery Invoice');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('sale')], ['name' => $setSubTitle]]);
            $saleProductDelivery = SaleProductDelivery::with('sale', 'sale.party', 'warehouse', 'product', 'product.unit')->where(['invoice_no' => $invoice_no])->get();
            return view('sale::deliveryInvoice', compact('saleProductDelivery'));
        } else {
            return $this->access_blocked();
        }
    }
    public function deliveryInvoiceGatePass($invoice_no)
    {
        if (permission('sale-delivery-invoice')) {
            $setTitle    = __('file.Sale');
            $setSubTitle = __('file.Gate Pass');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('sale')], ['name' => $setSubTitle]]);
            $saleProductDelivery = SaleProductDelivery::with('sale', 'sale.party', 'warehouse', 'product', 'product.unit')->where(['invoice_no' => $invoice_no])->get();
            return view('sale::deliveryGatePass', compact('saleProductDelivery'));
        } else {
            return $this->access_blocked();
        }
    }
    public function return($id)
    {
        if (permission('sale-return')) {
            $setTitle    = __('file.Sale');
            $setSubTitle = __('file.Return');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-edit', [['name' => $setTitle, 'link' => route('sale')], ['name' => $setSubTitle]]);
            $saleData    = $this->model->with('party', 'saleProductList', 'saleProductList.warehouse', 'saleProductList.product.category', 'saleProductList.product', 'saleProductList.product.unit')->findOrFail($id);
            $data = [
                'sale'       => $saleData,
                'invoiceNo'  => self::return . '-' . round(microtime(true) * 1000),
                'warehouses' => Warehouse::all(),
            ];
            return view('sale::return', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function returnStore(Request $request)
    {
        if ($request->ajax() && permission('sale-return')) {
            DB::beginTransaction();
            try {
                $saleProductReturn       = [];
                $sale                    = $this->model->findOrFail($request->sale_id);
                if ($request->has('sale')) {
                    foreach ($request->sale as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['return_qty'] && !empty($value['sub_total']))) {
                            $scale = $value['return_qty'] * $value['scale'] / $value['sel_qty'];
                            $saleProductReturn[]  = [
                                'sale_id'         =>  $request->sale_id,
                                'invoice_no'      =>  $request->invoice_no,
                                'warehouse_id'    =>  $value['warehouse_id'],
                                'product_id'      =>  $value['product_id'],
                                'price'           =>  $value['price'],
                                'scale'           =>  $scale,
                                'qty'             =>  $value['return_qty'],
                                'date'            =>  $request->return_date,
                            ];
                            $saleProduct       = SaleProduct::findOrFail($value['id']);
                            $warehouseProduct  = WarehouseProduct::firstWhere(['warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                            if (($saleProduct->return_qty + $value['return_qty'] > $saleProduct->delivery_qty) || ($saleProduct->return_scale + $scale > $saleProduct->delivery_scale)) {
                                return response()->json(['status' => 'error', 'message' => 'Return Quantity Not Be Greater Then Delivery Quantity']);
                            }
                            $saleProduct->update([
                                'return_scale' => $saleProduct->return_scale + $scale,
                                'return_qty'   => $saleProduct->return_qty + $value['return_qty'],
                            ]);
                            $warehouseProduct->update([
                                'scale'        => $warehouseProduct->scale + $scale,
                                'qty'          => $warehouseProduct->qty + $value['return_qty'],
                            ]);
                        }
                    }
                }
                $sale->update([
                    'total_return_qty'       => $sale->total_return_qty + $request->total_return_qty,
                    'total_return_sub_total' => $sale->total_return_sub_total + $request->total_return_sub_total
                ]);
                $sale->saleProductReturn()->attach($saleProductReturn);
                $this->model->flushCache();
                if ($sale->party_type == 1) {
                    $party       = ChartOfHead::firstWhere(['master_head' => 1, 'party_id' => $sale->party_id]);
                    $cohId       = $party->id;
                    $name        = $party->name;
                } else {
                    $cohId       = 22;
                    $name        = 'Walking Customer';
                }
                $narration       = $name . ' sale return product amount ' . $request->total_return_sub_total . ' invoice no -' . $sale->invoice_no;
                $this->balanceCredit($cohId, $sale->invoice_no, $narration, $sale->sale_date, $request->total_return_sub_total);
                $this->balanceDebit(17, $sale->invoice_no, $narration, $sale->sale_date, $request->total_return_sub_total);
                $output = ['status' => 'success', 'message' => 'Return Store Successfully'];
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
    public function returnInvoice($invoice_no)
    {
        if (permission('sale-return-invoice')) {
            $setTitle    = __('file.Sale');
            $setSubTitle = __('file.Return Invoice');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('sale')], ['name' => $setSubTitle]]);
            $saleProductReturn = SaleProductReturn::with('sale', 'sale.party', 'warehouse', 'product', 'product.unit')->where(['invoice_no' => $invoice_no])->get();
            return view('sale::returnInvoice', compact('saleProductReturn'));
        } else {
            return $this->access_blocked();
        }
    }
    public function returnInvoiceGatePass($invoice_no)
    {
        if (permission('sale-return-invoice')) {
            $setTitle    = __('file.Sale');
            $setSubTitle = __('file.Gate Pass');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('sale')], ['name' => $setSubTitle]]);
            $saleProductReturn = SaleProductReturn::with('sale', 'sale.party', 'warehouse', 'product', 'product.unit')->where(['invoice_no' => $invoice_no])->get();
            return view('sale::returnGatePass', compact('saleProductReturn'));
        } else {
            return $this->access_blocked();
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax() && permission('sale-delete')) {
            DB::beginTransaction();
            try {
                $sale = $this->model->with('saleProductList')->findOrFail($request->id);
                abort_if($sale->sale_status == 4, 404);
                $sale->saleProductList()->delete();
                $sale->delete();
                $this->model->flushCache();
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
    public function saleCategoryProduct($categoryId)
    {
        return Product::where(['category_id' => $categoryId])->get();
    }
    public function saleProductDetails($warehouseId, $productId)
    {
        $product          = Product::with('unit')->findOrFail($productId);
        $warehouseProduct = WarehouseProduct::firstWhere(['warehouse_id' => $warehouseId, 'product_id' => $productId]);
        return [
            'unitId'      => $product->unit->unit_name,
            'unitShow'    => $product->unit->unit_name . '(' . $product->unit->unit_code . ')',
            'salePrice'   => $product->sale_price,
            'availableQty' => !empty($warehouseProduct) ? $warehouseProduct->qty : 0
        ];
    }
    public function balanceDebit($cohId, $invoiceNo, $narration, $date, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => $date,
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => self::sale,
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
            'voucher_type'     => self::sale,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $paidAmount,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }
}
