<?php

namespace Modules\Tenant\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Entities\TenantDeliveryProduct;
use Modules\Tenant\Entities\TenantWarehouseProduct;
use Modules\Tenant\Http\Requests\TenantDeliveryProductFormRequest;

class TenantDeliveryProductController extends BaseController
{
    private const tdp = 'TENANT-DELIVERY-PRODUCT';
    public function __construct(TenantDeliveryProduct $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('tenant-delivery-access')) {
            $setTitle = __('file.Tenant Delivery');
            $this->setPageData($setTitle, $setTitle, 'fas fa-user-check', [['name' => $setTitle]]);
            $data = [
                'tenants' => Tenant::all(),
            ];
            return view('tenant::tenantDelivery.index', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('tenant-delivery-access')) {
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
                if (permission('tenant-delivery-show')) {
                    $action .= ' <a class="dropdown-item" href="' . route("tenant.delivery.product.view", $value->id) . '">' . $this->actionButton('Details') . '</a>';
                }
                if (permission('tenant-delivery-edit') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item" href="' . route("tenant.delivery.product.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('tenant-delivery-status-change') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '" data-status="' . $value->status . '">' . $this->actionButton('Change Status') . '</a>';
                }
                if (permission('tenant-delivery-delete') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->invoice_no;
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
        if (permission('tenant-delivery-add')) {
            $setTitle = __('file.Tenant Delivery');
            $this->setPageData($setTitle, $setTitle, 'fas fa-user-check', [['name' => $setTitle]]);
            $data = [
                'invoice_no'   => self::tdp . '-' . round(microtime(true) * 1000),
                'tenants'      => Tenant::all(),
                'warehouses'   => Warehouse::all(),
                'categories'   => Category::all(),
            ];
            return view('tenant::tenantDelivery.create', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function deliveryData(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if ($tenant_id) {
            $data['tenant_warehouse_products'] =  TenantWarehouseProduct::where(['tenant_id' => $tenant_id, 'tenant_product_type' => 2])->get();
            // return $data['tenant_warehouse_products'];

            return view('tenant::tenantDelivery.data', $data);
        }
    }

    public function store(TenantDeliveryProductFormRequest $request)
    {
        if ($request->ajax() && permission('tenant-delivery-add')) {
            DB::beginTransaction();
            try {
                $tenantDelivery = [];
                $collection     = collect($request->all())->except('_token', 'tenant_delivery')->merge(['status' => 2, 'created_by' => auth()->user()->name]);
                $result         = $this->model->create($collection->all());
                if ($request->has('tenant_delivery')) {
                    foreach ($request->tenant_delivery as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['del_qty'])) {
                            $tenantDelivery[] = [
                                'warehouse_id' => $value['warehouse_id'],
                                'product_id'   => $value['product_id'],
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'del_qty'      => $value['del_qty']
                            ];
                        }
                    }
                }
                $result->tenantDeliveryProduct()->attach($tenantDelivery);
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
        if (permission('tenant-delivery-show')) {
            $setTitle      = __('file.Tenant Delivery');
            $setSubTitle   = __('file.Tenant Delivery Details');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file', [['name' => $setTitle, 'link' => route('tenant.delivery.product')], ['name' => $setSubTitle]]);
            $tenantDelivery = $this->model->with('tenant', 'tenantDeliveryProductList.warehouse', 'tenantDeliveryProductList.product', 'tenantDeliveryProductList.product.unit')->findOrFail($id);
            return view('tenant::tenantDelivery.show', compact('tenantDelivery'));
        } else {
            return $this->access_blocked();
        }
    }
    public function edit($id)
    {
        if (permission('tenant-delivery-edit')) {
            $setTitle = __('file.Tenant Delivery Edit');
            $this->setPageData($setTitle, $setTitle, 'fas fa-user-check', [['name' => $setTitle]]);
            $data = [
                'tenantDelivery'   => $this->model->with('tenant', 'tenantDeliveryProductList.warehouse', 'tenantDeliveryProductList.product', 'tenantDeliveryProductList.product.unit')->findOrFail($id),
                'tenants'        => Tenant::all(),
                'warehouses'     => Warehouse::all(),
                'categories'     => Category::all(),
            ];
            return view('tenant::tenantDelivery.edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(TenantDeliveryProductFormRequest $request)
    {
        if ($request->ajax() && permission('tenant-delivery-edit')) {
            DB::beginTransaction();
            try {
                $tenantDelivery = [];
                $collection     = collect($request->all())->except('_token', 'tenant_delivery')->merge(['status' => 2, 'created_by' => auth()->user()->name]);
                $result         = $this->model->findOrFail($request->update_id);
                if ($request->has('tenant_delivery')) {
                    foreach ($request->tenant_delivery as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['del_qty'])) {
                            $tenantDelivery[Str::random(5)] = [
                                'warehouse_id' => $value['warehouse_id'],
                                'product_id'   => $value['product_id'],
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'del_qty'      => $value['del_qty']
                            ];
                        }
                    }
                }
                $result->update($collection->all());
                $result->tenantDeliveryProduct()->sync($tenantDelivery);
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
        if ($request->ajax() && permission('tenant-delivery-status-change')) {
            DB::beginTransaction();
            try {
                $tenantDelivery = $this->model->with('tenantDeliveryProductList')->findOrFail($request->id);
                abort_if($tenantDelivery->status == 1, 404);
                foreach ($tenantDelivery->tenantDeliveryProductList as $value) {
                    $tenantWarehouseProduct = TenantWarehouseProduct::firstWhere(['tenant_id' => $tenantDelivery->tenant_id, 'warehouse_id' => $value->warehouse_id, 'product_id' => $value->product_id, 'tenant_product_type' => 2]);
                    if ($value->del_qty > $tenantWarehouseProduct->qty || $value->scale > $tenantWarehouseProduct->scale) {
                        $output = ['status' => 'error', 'message' => 'Product Quantity Or Scale Not Enough'];
                        return response()->json($output);
                    }
                    $tenantWarehouseProduct->update([
                        'qty'                 => $tenantWarehouseProduct->qty - $value->del_qty,
                        'scale'               => $tenantWarehouseProduct->scale - $value->scale,
                    ]);
                }
                $tenantDelivery->update(['status'  =>  1]);
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
    public function delete(Request $request)
    {
        if ($request->ajax() && permission('tenant-delivery-delete')) {
            DB::beginTransaction();
            try {
                $tenantDelivery = $this->model->with('tenantDeliveryProductList')->findOrFail($request->id);
                abort_if($tenantDelivery->status == 1, 404);
                $tenantDelivery->tenantDeliveryProductList()->delete();
                $tenantDelivery->delete();
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
        $warehouseProduct = TenantWarehouseProduct::with('product.unit')->firstWhere(['warehouse_id' => $request->warehouseId, 'product_id' => $request->productId, 'tenant_id' => $request->tenant_id, 'tenant_product_type' => 2]);
        return [
            'unitId'      => !empty($warehouseProduct) ? $warehouseProduct->product->unit->unit_name : 0,
            'unitShow'    => !empty($warehouseProduct) ? $warehouseProduct->product->unit->unit_name . '(' . $warehouseProduct->product->unit->unit_code . ')' : 0,
            'availableQty' => !empty($warehouseProduct) ? $warehouseProduct->qty : 0,
            'scale'       => !empty($warehouseProduct) ? $warehouseProduct->scale : 0,
        ];
    }
}
