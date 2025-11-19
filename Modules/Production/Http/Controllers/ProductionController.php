<?php

namespace Modules\Production\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Transaction;
use Modules\Category\Entities\Category;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Expense\Entities\ExpenseItem;
use Modules\LaborHead\Entities\LaborHead;
use Modules\Mill\Entities\Mill;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;
use Modules\Production\Entities\Production;
use Modules\Production\Entities\ProductionBatch;
use Modules\Production\Entities\ProductionRawProduct;
use Modules\Production\Http\Requests\ProductionCompleteFormRequest;
use Modules\Production\Http\Requests\ProductionFormRequest;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;

class ProductionController extends BaseController
{
    private const p = 'PRODUCTION';
    public function __construct(Production $model)
    {
        return $this->model = $model;
    }
    public function index()
    {
        if (permission('production-access')) {
            $setTitle = __('file.Production');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = [
                'mills' => Mill::all()
            ];
            return view('production::index', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('production-access')) {
            if (!empty($request->invoice_no)) {
                $this->model->setInvoiceNo($request->invoice_no);
            }
            if (!empty($request->mill_id)) {
                $this->model->setMillId($request->mill_id);
            }
            if (!empty($request->start_date)) {
                $this->model->setStartDate($request->start_date);
            }
            if (!empty($request->end_date)) {
                $this->model->setEndDate($request->end_date);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('production-show')) {
                    $action .= ' <a class="dropdown-item view_data" href="' . route("production.show", $value->id) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('production-edit') and $value->production_status == 1) {
                    $action .= ' <a class="dropdown-item" href="' . route("production.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('production-status-change') and $value->production_status == 1) {
                    $action .= ' <a class="dropdown-item change_status" data-id="' . $value->id . '" data-status="' . $value->production_status . '">' . $this->actionButton('Change Status') . '</a>';
                }
                if (permission('production') and $value->production_status == 3) {
                    $action .= ' <a class="dropdown-item" href="' . route("production.product", $value->id) . '">' . $this->actionButton('Production Product') . '</a>';
                }
                if (permission('production-sale-details') and isset($value->productionSaleList)) {
                    foreach ($value->productionSaleList as $sale) {
                        $action .=  '<a class="dropdown-item" href="' . route("production.sale.show", $sale->id) . '">' . $this->actionButton('Sale') . '(' . $sale->sale_date . ')' . '</a>' .
                            '<a class="dropdown-item" href="' . route("production.sale.gate.pass", $sale->id) . '">' . $this->actionButton('Gate Pass') . '(' . $sale->sale_date . ')' . '</a>' .
                            '<a class="dropdown-item" href="' . route("production.sale.packing", $sale->id) . '">' . $this->actionButton('Packing') . '(' . $sale->sale_date . ')' . '</a>';
                    }
                }
                if (permission('production-product-details') and isset($value->productionProductInvoice)) {
                    foreach ($value->productionProductInvoice as $product) {
                        $action .= '<a class="dropdown-item" href="' . route("production.product.show", $product->invoice_no) . '">' . $this->actionButton('Stock') . '(' . $product->date . ')' . '</a>' .
                            '<a class="dropdown-item" href="' . route("production.product.packing", $product->invoice_no) . '">' . $this->actionButton('Stock Packing') . '(' . $product->date . ')' . '</a>' .
                            '<a class="dropdown-item" href="' . route("production.product.gate.pass", $product->invoice_no) . '">' . $this->actionButton('Stock Gate Pass') . '(' . $product->date . ')' . '</a>';
                    }
                }
                if (permission('production-summary') and $value->production_status == 4) {
                    $action .= ' <a class="dropdown-item" href="' . route("production.summary", $value->id) . '">' . $this->actionButton('Summary') . '</a>';
                }
                if (permission('production-report') and $value->production_status == 4) {
                    $action .= ' <a class="dropdown-item" href="' . route("production.report.details", $value->id) . '">' . $this->actionButton('Report') . '</a>';
                }
                if (permission('production-delete') and $value->production_status == 1) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->batch_no;
                $row[]  = $value->production_type;
                $row[]  = $value->invoice_no;
                $row[]  = $value->mill->name;
                $row[]  = $value->date;
                $row[]  = $value->start_date ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[]  = $value->end_date ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[]  = $value->total_raw_amount ?? 0;
                $row[]  = $value->total_milling ?? 0;
                $row[]  = $value->total_expense ?? 0;
                $row[]  = PRODUCTION_STATUS_LABEL[$value->production_status];
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
        if (permission('production-add')) {
            $setTitle = __('file.Production');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);

            $production_batch_count = ProductionBatch::orderBy('id', 'asc')->count();
            $batch_no = date('Y') . '-' . (($production_batch_count ?? 0) + 1);

            $data = [
                'invoice_no'  => self::p . '-' . round(microtime(true) * 1000),
                'mills'       => Mill::all(),
                'warehouses'  => Warehouse::all(),
                'parties'  => Party::where('status', 1)->get(),
                'products'  => Product::where('status', 1)->get(),
                // 'categories'  => Category::all(),
                'batch_no'  => $batch_no
            ];
            return view('production::create', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function store(ProductionFormRequest $request)
    {
        // return $request;
        if ($request->ajax() && permission('production-add')) {
            DB::beginTransaction();
            try {
                $production = [];
                $collection = collect($request->all())->except('_token', 'production')->merge(['created_by' => auth()->user()->name]);
                if ($request->has('production')) {
                    foreach ($request->production as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['pro_qty'])) {

                            $warehouse_product = WarehouseProduct::where(['party_id' => $value['party_id'], 'warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']])->first('purchase_price');

                            $production[] = [
                                'date'         => $request->date,
                                'warehouse_id' => $value['warehouse_id'],
                                'party_id' => $value['party_id'] ?? '',
                                'purchase_id' => $value['purchase_id'] ?? '',
                                'product_id'   => $value['product_id'],
                                'price'        => $warehouse_product->purchase_price ?? 0,
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'pro_qty'      => $value['pro_qty'],
                                'load_unload_rate'      => $value['load_unload_rate'] ?? '',
                                'load_unload_amount'      => $value['load_unload_amount'] ?? '',
                            ];
                        }
                    }
                }
                $data = $this->model->create($collection->all());

                $production_batch = ProductionBatch::create([
                    'production_id' => $data->id,
                    'batch_no' => $request->batch_no,
                ]);


                $data->productionRawProduct()->attach($production);
                $this->model->flushCache();
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
        if (permission('production-show')) {
            $setTitle = __('file.Production Details');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = $this->model->with('mill', 'productionRawProductList', 'productionRawProductList.warehouse', 'productionRawProductList.product', 'productionRawProductList.product.unit', 'productionRawProductList.party', 'productionRawProductList.purchase')->findOrFail($id);
            return view('production::details', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }
    public function edit($id)
    {
        if (permission('production-edit')) {
            $setTitle = __('file.Production Edit');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $edit     = $this->model->with('mill', 'productionRawProductList', 'productionRawProductList.warehouse', 'productionRawProductList.product', 'productionRawProductList.product.category', 'productionRawProductList.product.unit')->findOrFail($id);
            abort_if($edit->production_status == 4, 404);
            $data = [
                'edit'        => $edit,
                'mills'       => Mill::all(),
                'warehouses'  => Warehouse::all(),
                'parties'  => Party::where('status', 1)->get(),
                'categories'  => Category::all(),
            ];
            return view('production::edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(ProductionFormRequest $request)
    {
        // return $request;
        if ($request->ajax() && permission('production-edit')) {
            DB::beginTransaction();
            try {
                $production = [];
                $collection = collect($request->all())->except('_token', 'production')->merge(['modified_by' => auth()->user()->name]);
                $data       = $this->model->findOrFail($request->update_id);
                abort_if($data->production_status == 4, 404);
                if ($request->has('production')) {
                    foreach ($request->production as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['pro_qty'])) {
                            $warehouse_product = WarehouseProduct::where(['party_id' => $value['party_id'], 'warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']])->first('purchase_price');

                            $production[Str::random(5)] = [
                                'date'         => $request->date,
                                'warehouse_id' => $value['warehouse_id'],
                                'party_id' => $value['party_id'] ?? '',
                                'purchase_id' => $value['purchase_id'] ?? '',
                                'product_id'   => $value['product_id'],
                                'price'        => $warehouse_product->purchase_price ?? 0,
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'pro_qty'      => $value['pro_qty'],
                                'load_unload_rate'      => $value['load_unload_rate'] ?? '',
                                'load_unload_amount'      => $value['load_unload_amount'] ?? '',
                            ];
                        }
                    }
                }
                $data->update($collection->all());
                $data->productionRawProduct()->sync($production);
                $data->touch();
                $this->model->flushCache();
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
        if ($request->ajax() && permission('production-status-change')) {
            DB::beginTransaction();
            try {
                $collection = collect($request->all())->only('production_status');
                $data       = $this->model->with('productionRawProductList')->findOrFail($request->production_id);
                // return $data;

                if ($request->production_status == 3) {
                    // labour-bill-generate
                    $labor_head = LaborHead::find(1); // load-unload
                    $amount = $data->productionRawProductList()->sum('load_unload_amount');

                    $coh     = ChartOfHead::firstWhere(['labor_head_id' => $labor_head->id]);
                    $note = "Production In";
                    $this->labour_head_Credit($coh->id, $data->invoice_no, $note, $amount);
                }

                if ($request->production_status == 3) {
                    foreach ($data->productionRawProductList as $value) {
                        $warehouseProduct  = WarehouseProduct::firstWhere(['party_id' => $value['party_id'], 'warehouse_id' => $value['warehouse_id'], 'purchase_id' => $value['purchase_id'], 'product_id' => $value['product_id']]);
                        $warehouseProduct->update([
                            'scale'        => $warehouseProduct->scale - $value->scale,
                            'qty'          => $warehouseProduct->qty - $value->pro_qty,
                        ]);
                    }
                    $collection    = $collection->merge(['start_date' => date('Y-m-d')]);
                }
                $data->update($collection->all());
                $this->model->flushCache();
                $output = ['status' => 'success', 'message' => $this->responseMessage('Status Changed')];
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

    public function production($id)
    {
        if (permission('production')) {
            $setTitle = __('file.Production');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = [
                'production'        => $this->model
                    ->with('mill', 'productionRawProductList', 'productionRawProductList.warehouse', 'productionRawProductList.product', 'productionRawProductList.product.unit')
                    ->findOrFail($id),

                'productionSale'    => DB::table('production_sales as sp')
                    ->join('production_sale_products as psp', 'sp.id', '=', 'psp.id')
                    ->where(['sp.production_id' => $id])
                    ->select(
                        DB::raw('SUM(psp.scale) as scale'),
                        DB::raw('SUM(psp.sub_total) as subTotal'),
                        DB::raw('SUM(psp.use_qty) as productionSaleProductUseQty'),
                        DB::raw('SUM(psp.use_sub_total) as productionSaleProductUseSubTotal'),
                    )->get(),

                'productionProduct' => DB::table('production_products as pp')
                    ->where(['production_id' => $id])
                    ->select(
                        DB::raw('SUM(pp.scale) as scale'),
                        DB::raw('SUM(pp.sub_total) as subTotal'),
                        DB::raw('SUM(pp.use_qty) as productionProductUseQty'),
                        DB::raw('SUM(pp.use_sub_total) as productionProductUseSubTotal')
                    )->get(),

                'expenses'          => ExpenseItem::ProductionExpense()->get(),
                'byProduct'         => DB::table('production_products as pp')
                    ->join('products as p', 'p.id', '=', 'pp.product_id')
                    ->where(['pp.production_id' => $id, 'p.category_id' => '4'])
                    ->select(
                        DB::raw('COALESCE(SUM(pp.scale), 0) as scale'),
                        DB::raw('SUM(pp.sub_total) as subTotal'),
                        DB::raw('SUM(pp.use_qty) as byProductUseQty'),
                        DB::raw('SUM(pp.use_sub_total) as byProductUseSubTotal')
                    )->get(),

            ];
            // return $data['productionProduct'];

            return view('production::production', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function complete(ProductionCompleteFormRequest $request)
    {
        // return $request;
        if ($request->ajax() && permission('production-complete')) {
            DB::beginTransaction();
            try {
                $expense    = [];
                $collection = collect($request->all())->except('_token', 'production_id', 'raws', 'expense')->merge(['end_date' => date('Y-m-d'), 'production_status' => 4]);
                $data       = $this->model->findOrFail($request->production_id);
                $millCohId  = ChartOfHead::firstWhere(['mill_id' => $data->mill_id]);
                $narration  = 'Production Milling Cost';
                if ($request->has('raws')) {
                    foreach ($request->raws as $value) {
                        $productionProductRaw = ProductionRawProduct::firstWhere(['production_id' => $request->production_id, 'warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                        $warehouseProduct     = WarehouseProduct::firstWhere(['warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                        $productionProductRaw->update([
                            'use_qty'     => $value['use_qty'],
                            'use_scale'   => $value['use_scale'],
                            'use_pro_qty' => $value['use_pro_qty'],
                            'milling'     => $value['milling'] ?? 0,
                        ]);
                        $warehouseProduct->update([
                            'qty'   => $warehouseProduct->qty + $value['pro_qty'] - $value['use_pro_qty'],
                            'scale' => $warehouseProduct->scale + $value['scale'] - $value['use_scale']
                        ]);
                    }
                }
                if ($request->has('expense')) {
                    foreach ($request->expense as $value) {
                        if (!empty($value['expense_id']) && !empty($value['expense_cost'])) {
                            $expense[] = [
                                'expense_item_id' => $value['expense_id'],
                                'expense_cost'    => $value['expense_cost']
                            ];
                            $narration        = 'Production Expense Cost Invoice No -' . $data->invoice_no;
                            $expenseItemCohId = ChartOfHead::firstWhere(['master_head' => 7, 'expense_item_id' => $value['expense_id']]);
                            $this->balanceDebit($expenseItemCohId->id, $data->invoice_no, $narration, $data->date, $value['expense_cost']);
                            $this->balanceCredit(24, $data->invoice_no, $narration, $data->date, $value['expense_cost']);
                        }
                    }
                    $data->productionExpense()->attach($expense);
                }
                $data->update($collection->all());
                $this->balanceCredit($millCohId->id, $data->invoice_no, $narration, $data->date, $request->total_milling);
                $this->model->flushCache();
                $output     = ['status' => 'success', 'message' => $this->responseMessage('Data Saved')];
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
    public function reportDetails($id)
    {
        if (permission('production-report')) {
            $setTitle = __('file.Report');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = $this->model->with(
                'mill',
                'productionRawProductList.warehouse',
                'productionRawProductList.product',
                'productionRawProductList.product.unit',
                'productionExpenseList.expenseItem',
                'productionSaleList.productionSaleProductList.product',
                'productionSaleList.productionSaleProductList.product.category',
                'productionSaleList.productionSaleProductList.product.unit',
                'productionSaleList.productionSaleProductList.useWarehouse',
                'productionSaleList.productionSaleProductList.useProduct',
                'productionSaleList.productionSaleProductList.useProduct.unit',
                'productionProductList.warehouse',
                'productionProductList.product',
                'productionProductList.product.category',
                'productionProductList.product.unit',
                'productionProductList.useWarehouse',
                'productionProductList.useProduct',
                'productionProductList.useProduct.unit'
            )
                ->findOrFail($id);
            return view('production::report', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }
    public function summary($id)
    {
        if (permission('production-summary')) {
            $setTitle = __('file.Summary');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = $this->model->with('productionRawProductList.purchase','mill')->findOrFail($id);
            // return $data;

            return view('production::summarize', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax() && permission('production-delete')) {
            DB::beginTransaction();
            try {
                $production = $this->model->with('productionRawProductList')->findOrFail($request->id);
                abort_if($production->production_status == 4, 404);

                $production_batch = ProductionBatch::where(['production_id' => $request->id])->delete();

                $production->productionRawProductList()->delete();
                $production->delete();
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

    public function partyProduct(Request $request)
    {
        $warehouseId = $request->warehouseId;
        $partyId = $request->partyId;
        return WarehouseProduct::with('product')->where(['warehouse_id' => $warehouseId, 'party_id' => $partyId,])->groupBy('product_id')->get();
    }

    public function categoryProduct(Request $request)
    {
        $category_id = $request->category_id;
        return Product::where('category_id', $category_id)->get();
    }

    public function party_wise_purchase_invoice(Request $request)
    {
        $party_id = $request->party_id;
        $warehouse_id = $request->warehouse_id;
        $product_id = $request->product_id;
        return WarehouseProduct::with('purchase', 'product.unit')->where('qty', '>', 0)->where(['party_id' => $party_id, 'warehouse_id' => $warehouse_id, 'product_id' => $product_id,])->get();
    }

    public function availableProduct(Request $request)
    {
        $purchase_id = $request->purchase_id;
        $party_id = $request->party_id;
        $warehouse_id = $request->warehouse_id;
        $product_id = $request->product_id;
        return WarehouseProduct::where(['purchase_id' => $purchase_id, 'party_id' => $party_id, 'warehouse_id' => $warehouse_id, 'product_id' => $product_id,])->first();
    }

    public function productDetails($productId)
    {
        $product          = Product::with('unit')->findOrFail($productId);
        return [
            'unitId'      => $product->unit->unit_name,
            'unitShow'    => $product->unit->unit_name . '(' . $product->unit->unit_code . ')',
            'salePrice'   => $product->sale_price,
        ];
    }
    public function warehouseProduct($warehouseId, $productId)
    {
        $product          = Product::with('unit')->findOrFail($productId);
        $warehouseProduct = WarehouseProduct::firstWhere(['warehouse_id' => $warehouseId, 'product_id' => $productId]);
        return [
            'unitId'        => $product->unit->unit_name,
            'unitShow'      => $product->unit->unit_name . '(' . $product->unit->unit_code . ')',
            'salePrice'     => $product->sale_price,
            'purchasePrice' => $product->purchase_price,
            'availableQty'  => !empty($warehouseProduct) ? $warehouseProduct->qty : 0,
            'scale'         => !empty($warehouseProduct) ? $warehouseProduct->scale : 0
        ];
    }
    public function balanceDebit($cohId, $invoiceNo, $narration, $date, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => $date,
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => self::p,
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
            'voucher_type'     => self::p,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $paidAmount,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }
}
