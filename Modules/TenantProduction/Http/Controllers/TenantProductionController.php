<?php

namespace Modules\TenantProduction\Http\Controllers;

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
use Modules\Product\Entities\Product;
use Modules\Production\Entities\ProductionBatch;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Entities\TenantWarehouseProduct;
use Modules\TenantProduction\Entities\TenantProduction;
use Modules\TenantProduction\Entities\TenantProductionRawProduct;
use Modules\TenantProduction\Http\Requests\TenantProductionCompleteFormRequest;
use Modules\TenantProduction\Http\Requests\TenantProductionFormRequest;

class TenantProductionController extends BaseController
{
    private const tp = 'TENANT-PRODUCTION';

    public function __construct(TenantProduction $model)
    {
        return $this->model = $model;
    }

    public function index()
    {
        if (permission('tenant-production-access')) {
            $setTitle = __('file.Tenant Production');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = [
                'mills' => Mill::all()
            ];
            return view('tenantproduction::index', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('tenant-production-access')) {
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
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('tenant-production-show')) {
                    $action .= ' <a class="dropdown-item view_data" href="' . route("tenant.production.show", $value->id) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('tenant-production-edit') && $value->production_status == 1) {
                    $action .= ' <a class="dropdown-item" href="' . route("tenant.production.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('tenant-production-status-change') && $value->production_status == 1) {
                    $action .= ' <a class="dropdown-item change_status" data-id="' . $value->id . '" data-status="' . $value->production_status . '">' . $this->actionButton('Change Status') . '</a>';
                }
                if (permission('tenant-production-product') && $value->production_status == 3) {
                    $action .= ' <a class="dropdown-item" href="' . route("tenant.production.product", $value->id) . '">' . $this->actionButton('Production Product') . '</a>';
                }
                if (permission('tenant-production-delivery-details') && isset($value->deliveryList)) {
                    foreach ($value->deliveryList as $delivery) {
                        $action .= '<a class="dropdown-item" href="' . route("tenant.production.delivery.show", $delivery->id) . '">' . $this->actionButton('Delivery Invoice') . '(' . $delivery->date . ')' . '</a>' .
                            '<a class="dropdown-item" href="' . route("tenant.production.delivery.packing", $delivery->id) . '">' . $this->actionButton('Packing') . '(' . $delivery->date . ')' . '</a>';
                    }
                }
                if (permission('tenant-production-product-details') && isset($value->productList)) {
                    foreach ($value->productList as $product) {
                        $action .= '<a class="dropdown-item" href="' . route("tenant.production.product.show", $product->invoice_no) . '">' . $this->actionButton('Stock') . '(' . $product->date . ')' . '</a>' .
                            '<a class="dropdown-item" href="' . route("tenant.production.product.packing", $product->invoice_no) . '">' . $this->actionButton('Stock Packing') . '(' . $product->date . ')' . '</a>';
                    }
                }
                if (permission('tenant-production-summary') && $value->production_status == 4) {
                    $action .= ' <a class="dropdown-item" href="' . route("tenant.production.summary", $value->id) . '">' . $this->actionButton('Summary') . '</a>';
                }
                if (permission('tenant-production-report-details') && $value->production_status == 4) {
                    $action .= ' <a class="dropdown-item" href="' . route("tenant.production.report.details", $value->id) . '">' . $this->actionButton('Report') . '</a>';
                }
                if (permission('tenant-production-delete') && $value->production_status == 1) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row = [];
                $row[] = $no;
                $row[] = $value->invoice_no;
                $row[] = $value->tenant->name ?? '';
                $row[] = $value->mill->name ?? '';
                $row[] = $value->date;
                $row[] = $value->start_date ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[] = $value->end_date ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[] = $value->total_raw_scale ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[] = $value->total_milling ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[] = $value->total_expense ?? '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[] = PRODUCTION_STATUS_LABEL[$value->production_status];
                $row[] = $value->created_by;
                $row[] = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function create()
    {
        if (permission('tenant-production-add')) {
            $setTitle = __('file.Tenant Production');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);


            $production_batch_count = ProductionBatch::orderBy('id', 'asc')->count();
            $production_batch_no = date('Y') . '-' . (($production_batch_count ?? 0) + 1);


            $tenant_production_count = ProductionBatch::orderBy('id', 'desc')->whereNotNull('tenant_production_id')->count();
            $batch_no = date('Y') . '-' . (($tenant_production_count) + 1);

            $data = [
                'invoice_no' => self::tp . '-' . round(microtime(true) * 1000),
                'tenants' => Tenant::all(),
                'mills' => Mill::all(),
                'warehouses' => Warehouse::all(),
                'categories' => Category::all(),
                'batch_no' => $batch_no,
                'production_batch_no' => $production_batch_no,
            ];
            return view('tenantproduction::create', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function tenant_wise_production_detail(Request $request)
    {
        if (permission('tenant-production-add')) {
            $data = [
                'tenant_warehouse_products' => TenantWarehouseProduct::where(['tenant_id' => $request->tenant_id, 'tenant_product_type' => 1])->get(),
                // 'categories'  => Category::all(),
            ];
            return view('tenantproduction::tenant_wise_production_detail', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function store(TenantProductionFormRequest $request)
    {
        // return $request;
        if ($request->ajax() && permission('tenant-production-add')) {
            DB::beginTransaction();
            try {
                $production = [];
                $collection = collect($request->all())->except('_token', 'production', 'total_product_qty')->merge(['created_by' => auth()->user()->name]);
                if ($request->has('production')) {
                    foreach ($request->production as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['pro_qty'])) {
                            $production[] = [
                                'date' => $request->date,
                                'warehouse_id' => $value['warehouse_id'],
                                'batch_no' => $value['batch_no'],
                                'product_id' => $value['product_id'],
                                'qty' => $value['qty'],
                                'scale' => $value['scale'],
                                'pro_qty' => $value['pro_qty'],
                                'load_unload_rate' => $value['load_unload_rate'] ?? '',
                                'load_unload_amount' => $value['load_unload_amount'] ?? '',
                            ];
                        }
                    }
                }
                $data = $this->model->create($collection->all());

                $production_batch = ProductionBatch::create([
                    'tenant_production_id' => $data->id,
                    'batch_no' => $request->batch_no,
                ]);


                $data->raw()->attach($production);
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
        if (permission('tenant-production-show')) {
            $setTitle = __('file.Tenant Production Details');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = $this->model->with('tenant', 'mill', 'rawList', 'rawList.warehouse', 'rawList.product', 'rawList.product.unit')->findOrFail($id);
            //    return $data ;
            return view('tenantproduction::details', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }

    public function edit($id)
    {
        if (permission('tenant-production-edit')) {
            $setTitle = __('file.Tenant Production Edit');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $edit = $this->model->with('tenant', 'mill', 'rawList', 'rawList.warehouse', 'rawList.product', 'rawList.product.category', 'rawList.product.unit')->findOrFail($id);
            //    return $edit;
            abort_if($edit->production_status == 4, 404);
            $data = [
                'edit' => $edit,
                // 'tenants'     => Tenant::all(),
                'mills' => Mill::all(),
                'warehouses' => Warehouse::all(),
                'categories' => Category::all(),
            ];
            return view('tenantproduction::edit', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function update(TenantProductionFormRequest $request)
    {
        if ($request->ajax() && permission('tenant-production-edit')) {
            DB::beginTransaction();
            try {
                $production = [];
                $collection = collect($request->all())->except('_token', 'production', 'total_product_qty')->merge(['modified_by' => auth()->user()->name]);
                $data = $this->model->findOrFail($request->update_id);
                abort_if($data->production_status == 4, 404);
                if ($request->has('production')) {
                    foreach ($request->production as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['pro_qty'])) {
                            $production[Str::random(5)] = [
                                'date' => $request->date,
                                'warehouse_id' => $value['warehouse_id'],
                                'batch_no' => $value['batch_no'],
                                'product_id' => $value['product_id'],
                                'qty' => $value['qty'],
                                'scale' => $value['scale'],
                                'pro_qty' => $value['pro_qty'],
                                'load_unload_rate' => $value['load_unload_rate'] ?? '',
                                'load_unload_amount' => $value['load_unload_amount'] ?? '',
                            ];
                        }
                    }
                }
                $data->update($collection->all());
                $data->raw()->sync($production);
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
                $data = $this->model->with('rawList')->findOrFail($request->production_id);


                if ($request->production_status == 3) {
                    // labour-bill-generate
                    $labor_head = LaborHead::find(1); // load-unload
                    $amount = $data->rawList()->sum('load_unload_amount');

                    $coh = ChartOfHead::firstWhere(['labor_head_id' => $labor_head->id]);
                    $note = "Tenant Production In";
                    $this->labour_head_Credit($coh->id, $data->invoice_no, $note, $amount);
                }


                if ($request->production_status == 3) {
                    foreach ($data->rawList as $value) {
                        $warehouseProduct = TenantWarehouseProduct::firstWhere(['tenant_id' => $data->tenant_id, 'batch_no' => $value->batch_no, 'warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                        $warehouseProduct->update([
                            'scale' => $warehouseProduct->scale - $value->scale,
                            'qty' => $warehouseProduct->qty - $value->pro_qty,
                        ]);
                    }
                    $collection = $collection->merge(['start_date' => date('Y-m-d')]);
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
            'date' => date('Y-m-d'),
            'voucher_no' => $invoiceNo,
            'voucher_type' => "LABOR-BILL",
            'narration' => $narration,
            'debit' => 0,
            'credit' => $paidAmount,
            'status' => 1,
            'is_opening' => 2,
            'created_by' => auth()->user()->name,
        ]);
    }

    public function production($id)
    {
        if (permission('tenant-production-product')) {
            $setTitle = __('file.Tenant Production');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = [
                'production' => $this->model
                    ->with('tenant', 'mill', 'rawList', 'rawList.warehouse', 'rawList.product', 'rawList.product.unit')
                    ->findOrFail($id),
                'productionDelivery' => DB::table('tenant_production_deliveries as tpd')
                    ->join('tenant_production_delivery_products as tpdp', 'tpd.id', '=', 'tpdp.id')
                    ->where(['tpd.tenant_production_id' => $id])
                    ->select(
                        DB::raw('SUM(tpdp.scale) as scale'),
                        DB::raw('SUM(tpdp.use_qty) as productionDeliveryProductUseQty'),
                    )->get(),
                'productionProduct' => DB::table('tenant_production_products as tpp')
                    ->where(['tpp.tenant_production_id' => $id])
                    ->select(
                        DB::raw('SUM(tpp.scale) as scale'),
                        DB::raw('SUM(tpp.use_qty) as productionProductUseQty'),
                    )->get(),
                'expenses' => ExpenseItem::ProductionExpense()->get(),
                'warehouses' => Warehouse::all(),
                'categories' => Category::all()
            ];
            return view('tenantproduction::production', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function complete(TenantProductionCompleteFormRequest $request)
    {
        if ($request->ajax() && permission('tenant-production-complete')) {
            DB::beginTransaction();
            try {
                $merge = [];
                $expense = [];
                $collection = collect($request->all())->except('_token', 'production_id', 'raws', 'merge', 'expense')->merge(['end_date' => date('Y-m-d'), 'production_status' => 4]);
                $data = $this->model->findOrFail($request->tenant_production_id);
                $millCohId = ChartOfHead::firstWhere(['mill_id' => $data->mill_id]);
                $tenantCohId = ChartOfHead::firstWhere(['tenant_id' => $data->tenant_id]);
                $narration = 'Production Milling Cost';
                if ($request->has('raws')) {
                    foreach ($request->raws as $value) {
                        $tenantProductionProductRaw = TenantProductionRawProduct::firstWhere(['tenant_production_id' => $request->tenant_production_id, 'warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                        $tenantWarehouseProduct = TenantWarehouseProduct::firstWhere(['tenant_id' => $request->tenant_id, 'warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                        $tenantProductionProductRaw->update([
                            'use_qty' => $value['use_qty'],
                            'use_scale' => $value['use_scale'],
                            'use_pro_qty' => $value['use_pro_qty'],
                            'milling' => $value['milling'] ?? 0,
                        ]);
                        $tenantWarehouseProduct->update([
                            'qty' => $tenantWarehouseProduct->qty + $value['pro_qty'] - $value['use_pro_qty'],
                            'scale' => $tenantWarehouseProduct->scale + $value['scale'] - $value['use_scale']
                        ]);
                    }
                }
                if ($request->has('merge')) {
                    foreach ($request->merge as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['price']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['mer_qty']) && !empty($value['rate']) && !empty($value['milling'])) {
                            $merge[] = [
                                'invoice_no' => $data->invoice_no,
                                'date' => date('Y-m-d'),
                                'warehouse_id' => $value['warehouse_id'],
                                'product_id' => $value['product_id'],
                                'price' => $value['price'],
                                'qty' => $value['qty'],
                                'scale' => $value['scale'],
                                'mer_qty' => $value['mer_qty'],
                                'sub_total' => $value['qty'] * $value['price'],
                                'milling' => $value['milling'] ?? 0,
                                'type' => 1,
                            ];
                            $warehouseProduct = WarehouseProduct::firstWhere(['warehouse_id' => $value['warehouse_id'], 'product_id' => $value['product_id']]);
                            if (!empty($warehouseProduct)) {
                                $warehouseProduct->update([
                                    'qty' => $warehouseProduct->qty - $value['mer_qty'],
                                    'scale' => $warehouseProduct->scale - $value['scale']
                                ]);
                            }
                        }
                    }
                    $data->mergeProduct()->attach($merge);
                }
                if ($request->has('expense')) {
                    foreach ($request->expense as $value) {
                        if (!empty($value['expense_id']) && !empty($value['expense_cost'])) {
                            $expense[] = [
                                'expense_item_id' => $value['expense_id'],
                                'expense_cost' => $value['expense_cost']
                            ];
                            $narration = 'Production Expense Cost Invoice No -' . $data->invoice_no;
                            $expenseItemCohId = ChartOfHead::firstWhere(['master_head' => 7, 'expense_item_id' => $value['expense_id']]);
                            $this->balanceDebit($expenseItemCohId->id, $data->invoice_no, $narration, $data->date, $value['expense_cost']);
                            $this->balanceCredit(24, $data->invoice_no, $narration, $data->date, $value['expense_cost']);
                        }
                    }
                    $data->expense()->attach($expense);
                }
                $data->update($collection->all());
                $this->balanceCredit($millCohId->id, $data->invoice_no, $narration, $data->date, $request->total_milling);
                $this->balanceDebit($tenantCohId->id, $data->invoice_no, $narration, $data->date, $request->total_milling);
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

    public function reportDetails($id)
    {
        if (permission('tenant-production-report-details')) {
            $setTitle = __('file.Report');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = $this->model
                ->with(
                    'mill',
                    'tenant',
                    'rawList.warehouse',
                    'rawList.product',
                    'rawList.product.unit',
                    'expenseList.expenseItem',
                    'deliveryList.deliveryProductList.product.unit',
                    'deliveryList.deliveryProductList.useWarehouse',
                    'deliveryList.deliveryProductList.useProduct.category',
                    'deliveryList.deliveryProductList.useProduct.unit',
                    'productList.product.unit',
                    'productList.useWarehouse',
                    'productList.useProduct.category',
                    'productList.useProduct.unit',
                    'mergeProductList.warehouse',
                    'mergeProductList.product.category',
                    'mergeProductList.product.unit'
                )
                ->findOrFail($id);
            return view('tenantproduction::report', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }

    public function summary($id)
    {
        if (permission('tenant-production-summary')) {
            $setTitle = __('file.Summary');
            $this->setPageData($setTitle, $setTitle, 'fas fa-industry', [['name' => $setTitle]]);
            $data = $this->model->with('mill')->findOrFail($id);
            return view('tenantproduction::summarize', compact('data'));
        } else {
            return $this->access_blocked();
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax() && permission('tenant-production-delete')) {
            DB::beginTransaction();
            try {
                $production = $this->model->with('rawList')->findOrFail($request->id);
                abort_if($production->production_status == 4, 404);
                $production_batch = ProductionBatch::where(['tenant_production_id' => $request->id])->delete();

                $production->rawList()->delete();
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

    public function categoryProduct($categoryId)
    {
        return Product::where(['category_id' => $categoryId])->get();
    }

    public function productDetails($productId)
    {
        $product = Product::with('unit')->findOrFail($productId);
        return [
            'unitId' => $product->unit->unit_name,
            'unitShow' => $product->unit->unit_name . '(' . $product->unit->unit_code . ')',
            'salePrice' => $product->sale_price,
        ];
    }

    public function warehouseProduct($tenantId, $warehouseId, $productId, $batch_no)
    {
        // return [$tenantId, $warehouseId, $productId, $batch_no];
        $product = Product::with('unit')->findOrFail($productId);
        $warehouseProduct = TenantWarehouseProduct::firstWhere(['tenant_id' => $tenantId, 'warehouse_id' => $warehouseId, 'product_id' => $productId, 'batch_no' => $batch_no]);

        return [
            'unitId' => $product->unit->unit_name,
            'unitShow' => $product->unit->unit_name . '(' . $product->unit->unit_code . ')',
            'availableQty' => !empty($warehouseProduct) ? $warehouseProduct->qty : 0,
            'scale' => !empty($warehouseProduct) ? $warehouseProduct->scale : 0
        ];
    }

    public function mergeWarehouseProduct($warehouseId, $productId)
    {
        $product = Product::with('unit')->findOrFail($productId);
        $warehouseProduct = WarehouseProduct::firstWhere(['warehouse_id' => $warehouseId, 'product_id' => $productId]);
        return [
            'unitId' => $product->unit->unit_name,
            'unitShow' => $product->unit->unit_name . '(' . $product->unit->unit_code . ')',
            'salePrice' => $product->sale_price,
            'purchasePrice' => $product->purchase_price,
            'availableQty' => !empty($warehouseProduct) ? $warehouseProduct->qty : 0,
            'scale' => !empty($warehouseProduct) ? $warehouseProduct->scale : 0
        ];
    }

    public function balanceDebit($cohId, $invoiceNo, $narration, $date, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date' => $date,
            'voucher_no' => $invoiceNo,
            'voucher_type' => self::tp,
            'narration' => $narration,
            'debit' => $paidAmount,
            'credit' => 0,
            'status' => 1,
            'is_opening' => 2,
            'created_by' => auth()->user()->name,
        ]);
    }

    public function balanceCredit($cohId, $invoiceNo, $narration, $date, $paidAmount)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date' => $date,
            'voucher_no' => $invoiceNo,
            'voucher_type' => self::tp,
            'narration' => $narration,
            'debit' => 0,
            'credit' => $paidAmount,
            'status' => 1,
            'is_opening' => 2,
            'created_by' => auth()->user()->name,
        ]);
    }
}
