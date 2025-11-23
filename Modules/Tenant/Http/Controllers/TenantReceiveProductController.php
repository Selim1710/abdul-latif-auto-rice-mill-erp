<?php

namespace Modules\Tenant\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Account\Entities\Transaction;
use Modules\Category\Entities\Category;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\LaborHead\Entities\LaborHead;
use Modules\Product\Entities\Product;
use Modules\Production\Entities\ProductionBatch;
use Modules\Setting\Entities\Warehouse;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Entities\TenantReceiveProduct;
use Modules\Tenant\Entities\TenantWarehouseProduct;
use Modules\Tenant\Http\Requests\TenantReceiveProductFormRequest;

class TenantReceiveProductController extends BaseController
{
    private const trp = 'TENANT-RECEIVE-PRODUCT';
    public function __construct(TenantReceiveProduct $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('tenant-receive-access')) {
            $setTitle = __('file.Tenant Receive');
            $this->setPageData($setTitle, $setTitle, 'fas fa-user-check', [['name' => $setTitle]]);
            $data = [
                'tenants'  => Tenant::all(),
            ];
            return view('tenant::tenantReceive.index', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('tenant-receive-access')) {
            if (!empty($request->tenant_id)) {
                $this->model->setTenantID($request->tenant_id);
            }
            $this->set_datatable_default_properties($request);
            $list  = $this->model->getDatatableList();
            $data  = [];
            $no    = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action      = '';
                if (permission('tenant-receive-show')) {
                    $action .= ' <a class="dropdown-item" href="' . route("tenant.receive.product.view", $value->id) . '">' . $this->actionButton('Details') . '</a>';
                }
                if (permission('tenant-receive-status-change') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '" data-status="' . $value->status . '">' . $this->actionButton('Change Status') . '</a>';
                }
                if (permission('tenant-receive-edit') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item" href="' . route("tenant.receive.product.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('tenant-receive-delete') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->invoice_no;
                $row[]  = $value->batch_no ?? '';
                $row[]  = $value->tenant->name . '(' . $value->tenant->mobile . ')';
                $row[]  = $value->date;
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">' . $value->created_by . '</span>';
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
        if (permission('tenant-receive-add')) {
            $setTitle = __('file.Tenant Receive');
            $this->setPageData($setTitle, $setTitle, 'fas fa-user-check', [['name' => $setTitle]]);

            $data = [
                'invoice_no'   => self::trp . '-' . round(microtime(true) * 1000),
                'tenants'      => Tenant::all(),
                'warehouses'   => Warehouse::all(),
                'categories'   => Category::all(),
            ];
            return view('tenant::tenantReceive.create', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function batchNo(Request $request)
    {
        $tenant_id = $request->tenant_id;
        $tenant_received_count = TenantReceiveProduct::where('tenant_id', $tenant_id)->count();
        $batch_no = date('Y') . '-' . ($tenant_received_count + 1);
        return $batch_no;
    }

    public function store(TenantReceiveProductFormRequest $request)
    {
        if ($request->ajax() && permission('tenant-receive-add')) {
            DB::beginTransaction();
            try {
                $tenantReceive = [];
                $collection    = collect($request->all())->except('_token', 'tenant_receive')->merge(['status' => 2, 'created_by' => auth()->user()->name]);
                $result        = $this->model->create($collection->all());
                if ($request->has('tenant_receive')) {
                    foreach ($request->tenant_receive as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['rec_qty'])) {
                            $tenantReceive[] = [
                                'warehouse_id' => $value['warehouse_id'],
                                'product_id'   => $value['product_id'],
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'rec_qty'      => $value['rec_qty'],
                                'load_unload_rate' => $value['load_unload_rate'] ?? '',
                                'load_unload_amount' => $value['load_unload_amount'] ?? '',
                            ];
                        }
                    }
                }
                $result->tenantReceiveProduct()->attach($tenantReceive);
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

    public function show($id)
    {
        if (permission('tenant-receive-show')) {
            $setTitle      = __('file.Tenant Receive');
            $setSubTitle   = __('file.Tenant Receive Details');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('tenant.receive.product')], ['name' => $setSubTitle]]);
            $tenantReceive = $this->model->with('tenant', 'tenantReceiveProductList.warehouse', 'tenantReceiveProductList.product', 'tenantReceiveProductList.product.unit')->findOrFail($id);
            return view('tenant::tenantReceive.show', compact('tenantReceive'));
        } else {
            return $this->access_blocked();
        }
    }

    public function edit($id)
    {
        if (permission('tenant-receive-edit')) {
            $setTitle = __('file.Tenant Receive Edit');
            $this->setPageData($setTitle, $setTitle, 'fas fa-user-check', [['name' => $setTitle]]);
            $data = [
                'tenantReceive'   => $this->model->with('tenant', 'tenantReceiveProductList', 'tenantReceiveProductList.warehouse', 'tenantReceiveProductList.product', 'tenantReceiveProductList.product.category', 'tenantReceiveProductList.product.unit')->findOrFail($id),
                'tenants'         => Tenant::all(),
                'warehouses'      => Warehouse::all(),
                'categories'      => Category::all(),
            ];
            return view('tenant::tenantReceive.edit', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function update(TenantReceiveProductFormRequest $request)
    {
        if ($request->ajax() && permission('tenant-receive-edit')) {
            DB::beginTransaction();
            try {
                $tenantReceive = [];
                $collection    = collect($request->all())->except('_token', 'tenant_receive');
                $result        = $this->model->findOrFail($request->update_id);
                if ($request->has('tenant_receive')) {
                    foreach ($request->tenant_receive as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['rec_qty'])) {
                            $tenantReceive[Str::random(5)] = [
                                'warehouse_id' => $value['warehouse_id'],
                                'product_id'   => $value['product_id'],
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'rec_qty'      => $value['rec_qty'],
                                'load_unload_rate' => $value['load_unload_rate'] ?? '',
                                'load_unload_amount' => $value['load_unload_amount'] ?? '',
                            ];
                        }
                    }
                }
                $result->update($collection->all());
                $result->tenantReceiveProduct()->sync($tenantReceive);
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

    public function changeStatus(Request $request)
    {
        if ($request->ajax() && permission('tenant-receive-status-change')) {
            DB::beginTransaction();
            try {
                $tenantReceive = $this->model->with('tenantReceiveProductList')->findOrFail($request->id);
                $tenantReceiveDate = $tenantReceive->date;
                abort_if($tenantReceive->status == 1, 404);

                // labour-bill-generate
                $labor_head = LaborHead::find(1); // load-unload
                $amount = $tenantReceive->tenantReceiveProductList()->sum('load_unload_amount');

                $coh     = ChartOfHead::firstWhere(['labor_head_id' => $labor_head->id]);
                $note = "Tenant Receive Load/Unload Bill for Invoice No: " . $tenantReceive->invoice_no;
                $this->labour_head_Credit($coh->id, $tenantReceive->invoice_no, $note, $amount, $tenantReceiveDate);

                foreach ($tenantReceive->tenantReceiveProductList as $value) {
                    $tenantWarehouseProduct = TenantWarehouseProduct::firstWhere(['tenant_id' => $tenantReceive->tenant_id, 'warehouse_id' => $value->warehouse_id, 'batch_no' => $value->batch_no, 'product_id' => $value->product_id, 'tenant_product_type' => 1]);
                    if (empty($tenantWarehouseProduct)) {
                        TenantWarehouseProduct::create([
                            'tenant_id'           => $tenantReceive->tenant_id,
                            'batch_no'        => $tenantReceive->batch_no ?? '',
                            'warehouse_id'        => $value->warehouse_id,
                            'product_id'          => $value->product_id,
                            'qty'                 => $value->rec_qty,
                            'scale'               => $value->scale,
                            'tenant_product_type' => 1
                        ]);
                    } else {
                        $tenantWarehouseProduct->update([
                            'qty'                 => $tenantWarehouseProduct->qty + $value->rec_qty,
                            'scale'               => $tenantWarehouseProduct->scale + $value->scale,
                        ]);
                    }
                }
                $tenantReceive->update([
                    'status'  =>  1
                ]);
                $output = ['status' => 'success', 'message' => 'Status Change Successfully'];
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

    public function labour_head_Credit($cohId, $invoiceNo, $narration, $paidAmount, $date)
    {
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => $date,
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
        if ($request->ajax() && permission('tenant-receive-delete')) {
            DB::beginTransaction();
            try {
                $tenantReceive = $this->model->with('tenantReceiveProductList')->findOrFail($request->id);
                abort_if($tenantReceive->status == 1, 404);
                $tenantReceive->tenantReceiveProductList()->delete();
                $tenantReceive->delete();
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

    public function categoryProduct(Request $request)
    {
        return Product::where(['category_id' => $request->categoryId])->get();
    }

    public function productDetails(Request $request)
    {
        return Product::with('unit')->findOrFail($request->productId);
    }
}
