<?php

namespace Modules\Stock\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use App\Http\Controllers\BaseController;
use Modules\Party\Entities\Party;
use Modules\Stock\Entities\WarehouseProduct;

class ProductStockController extends BaseController
{
    public function __construct(WarehouseProduct $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('finished-goods-stock-access')) {
            $setTitle = __('file.Products Stock');
            $this->setPageData($setTitle, $setTitle, 'fas fa-boxes', [['name' => $setTitle]]);
            $data = [
                'categories' => Category::all(),
                'products'   => Product::all(),
                'warehouses' => Warehouse::all(),
                'parties' => Party::all(),
            ];
            return view('stock::product.index', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function get_product_stock_data(Request $request)
    {
        if ($request->ajax() && permission('finished-goods-stock-access')) {
            if (!empty($request->category_id)) {
                $this->model->setCategoryID($request->category_id);
            }
            if (!empty($request->product_id)) {
                $this->model->setProductID($request->product_id);
            }
            
            if (!empty($request->warehouse_id)) {
                $this->model->setWarehouseID($request->warehouse_id);
            }

            if (!empty($request->party_id)) {
                $this->model->set_party_id($request->party_id);
            }

            $this->set_datatable_default_properties($request); //set datatable default properties
            $list = $this->model->getDatatableList();
            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->warehouseName;
                $row[]  = $value->invoice_no ?? '';
                $row[]  = $value->party_name ?? '';
                $row[]  = $value->productName;
                $row[]  = $value->productCode;
                $row[]  = '<span class="label label-danger label-pill label-inline" style="min-width:100px !important;">' . $value->categoryName . '</span>';
                $row[]  = $value->unitName . '(' . $value->unitCode . ')';
                $row[]  = number_format($value->purchase_price, 2);
                $row[]  = number_format($value->productPrice, 2);
                $row[]  = $value->scale;
                $row[]  = $value->qty;
                $row[]  = number_format(($value->productPrice * $value->qty), 2);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
        } else {
            return response()->json($this->unauthorized());
        }
    }
}
