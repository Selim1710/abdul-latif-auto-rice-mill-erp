<?php

namespace Modules\Tenant\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Entities\TenantWarehouseProduct;

class TenantWarehouseProductController extends BaseController
{
    public function __construct(TenantWarehouseProduct $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('tenant-stock-access')) {
            $setTitle = __('file.Products Stock');
            $this->setPageData($setTitle, $setTitle, 'fas fa-boxes', [['name' => $setTitle]]);
            $data = [
                'tenants'    => Tenant::all(),
                'warehouses' => Warehouse::all(),
                'products'   => Product::all(),
                'categories' => Category::all(),
            ];
            return view('tenant::tenantStock.index', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('tenant-stock-access')) {
            if (!empty($request->tenant_id)) {
                $this->model->setTenantID($request->tenant_id);
            }
            if (!empty($request->warehouse_id)) {
                $this->model->setWarehouseID($request->warehouse_id);
            }
            if (!empty($request->product_id)) {
                $this->model->setProductID($request->product_id);
            }
            if (!empty($request->category_id)) {
                $this->model->setCategoryID($request->category_id);
            }
            $this->set_datatable_default_properties($request); //set datatable default properties
            $list = $this->model->getDatatableList();

            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->batch_no ?? '';
                $row[]  = $value->tenantName;
                $row[]  = $value->warehouseName;
                $row[]  = $value->productName;
                $row[]  = $value->productCode;
                $row[]  = '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">' . $value->categoryName . '</span>';
                $row[]  = $value->unitName . '(' . $value->unitCode . ')';
                $row[]  = TENANT_PRODUCTION_LABEL[$value->tenant_product_type];
                $row[]  = $value->qty;
                $row[]  = $value->scale;
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
        } else {
            return response()->json($this->unauthorized());
        }
    }
}
