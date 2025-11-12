<?php

namespace Modules\Product\Http\Controllers;

use Exception;
use Keygen\Keygen;
use App\Models\Unit;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use App\Http\Controllers\BaseController;
use Modules\Product\Http\Requests\ProductFormRequest;
use Modules\Stock\Entities\WarehouseProduct;

class ProductController extends BaseController
{
    use UploadAble;
    public function __construct(Product $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('product-access')) {
            $setTitle = __('file.Product Manage');
            $this->setPageData($setTitle, $setTitle, 'fab fa-product-hunt', [['name' => $setTitle]]);
            $data = [
                'categories' => Category::all(),
            ];
            return view('product::index', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request)
    {
        if ($request->ajax() && permission('product-access')) {
            if (!empty($request->product_name)) {
                $this->model->setProductName($request->product_name);
            }
            if (!empty($request->product_code)) {
                $this->model->setProductCode($request->product_code);
            }
            if (!empty($request->category_id)) {
                $this->model->setCategoryId($request->category_id);
            }
            if (!empty($request->status)) {
                $this->model->setStatus($request->status);
            }
            $this->set_datatable_default_properties($request); //set datatable default properties
            $list = $this->model->getDatatableList(); //get table data
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('product-edit')) {
                    $action .= ' <a class="dropdown-item" href="' . route("product.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('product-view')) {
                    $action .= ' <a class="dropdown-item" href="' . url("product/view/" . $value->id) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('product-delete')) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $this->table_image(PRODUCT_IMAGE_PATH, $value->image, $value->name);
                $row[]  = $value->product_name ?? '';
                $row[]  = $value->product_code ?? '';
                $row[]  = $value->category->category_name ?? '';
                $row[]  = $value->unit->unit_name . '(' . $value->unit->unit_code . ')';
                $row[]  = $value->purchase_price ?? '';
                $row[]  = $value->sale_price ?? '';
                $row[]  = '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">' . $value->alert_qty . '</span>';
                $row[]  = OPENING_STOCK_LABLE[$value->opening_stock];
                $row[]  = STATUS_LABEL[$value->status];
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
        if (permission('product-add')) {
            $setTitle    = __('file.Product');
            $setSubTitle = __('file.Add Product');
            $this->setPageData($setSubTitle, $setSubTitle, 'fab fa-product-hunt', [['name' => $setTitle, 'link' => route('product')], ['name' => $setSubTitle]]);
            $data = [
                'categories' => Category::all(),
                'units'      => Unit::where([['base_unit', '!=', null]])->get(),
                'warehouses' => Warehouse::all(),
            ];
            return view('product::create', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function storeOrupdate(ProductFormRequest $request)
    {
        if ($request->ajax() && permission('product-add')) {
            DB::beginTransaction();
            try {
                $openingStock = [];
                $collection   = collect($request->all())->except('_token', 'update_id')->merge(['status' => 1]);
                if ($request->hasFile('image')) {
                    if (!empty($request->old_image)) {
                        $this->delete_file($request->old_image, PRODUCT_IMAGE_PATH);
                    }
                    $image       = $this->upload_file($request->file('image'), PRODUCT_IMAGE_PATH);
                    $collection  = $collection->merge(compact('image'));
                }
                if ($request->update_id) {
                    $product     = $this->model->with('openingStockList')->findOrFail($request->update_id);
                    if (isset($product->openingStockList)) {
                        foreach ($product->openingStockList as $value) {
                            $warehouseProductUpdate  = WarehouseProduct::firstWhere(['warehouse_id' => $value->warehouse_id, 'product_id' => $value->product_id]);
                            if (!empty($warehouseProductUpdate)) {
                                $warehouseProductUpdate->update([
                                    'scale'   => $warehouseProductUpdate->scale - $value->scale,
                                    'qty'     => $warehouseProductUpdate->qty   - $value->qty,
                                ]);
                            }
                        }
                    }
                }
                $collection = $this->track_data($collection, $request->update_id);
                $result     = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
                if ($request->opening_stock == 1) {
                    foreach ($request->openingStock as $value) {
                        if (!empty($value['warehouse_id']) && !empty($value['scale']) && !empty($value['qty'])) {
                            $openingStock[Str::random(5)] = [
                                'warehouse_id' => $value['warehouse_id'],
                                'scale'        => $value['scale'],
                                'qty'          => $value['qty']
                            ];
                            $warehouseProduct = WarehouseProduct::firstOrNew([
                                'warehouse_id'   => $value['warehouse_id'],
                                'product_id'     => $result->id
                            ], [
                                'scale'        => $value['scale'],
                                'qty'          => $value['qty']
                            ]);
                            if (!empty($warehouseProduct)) {
                                $warehouseProduct->update([
                                    'scale'      => $warehouseProduct->scale + $value['scale'],
                                    'qty'        => $warehouseProduct->qty   + $value['qty']
                                ]);
                            }
                            $warehouseProduct->save();
                        }
                    }
                }
                $result->openingStock()->sync($openingStock);
                $output     = $this->store_message($result, $request->update_id);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                $output     = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function edit(int $id)
    {
        if (permission('product-edit')) {
            $setTitle    = __('file.Product');
            $setSubTitle = __('file.Edit Product');
            $this->setPageData($setSubTitle, $setSubTitle, 'fab fa-pencil', [['name' => $setTitle, 'link' => route('product')], ['name' => $setSubTitle]]);
            $data = [
                'product'    => $this->model->with('openingStockList')->findOrFail($id),
                'categories' => Category::all(),
                'units'      => Unit::where([['base_unit', '!=', null]])->get(),
                'warehouses' => Warehouse::all(),
            ];
            return view('product::edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function show(int $id)
    {
        if (permission('product-view')) {
            $setTitle    = __('file.Product');
            $setSubTitle = __('file.Product Details');
            $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-paste', [['name' => $setTitle, 'link' => route('product')], ['name' => $setSubTitle]]);
            $product     = $this->model->with('category', 'unit', 'openingStockList.warehouse', 'openingStockList.product')->findOrFail($id);
            return view('product::details', compact('product'));
        } else {
            return $this->access_blocked();
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax() && permission('product-delete')) {
            DB::beginTransaction();
            try {
                $product   = $this->model->with('warehouseProduct', 'openingStockList')->findOrFail($request->id);
                $old_image = $product ? $product->image : '';
                if (isset($product->openingStockList)) {
                    $product->openingStockList()->delete();
                }
                if (isset($product->warehouseProduct)) {
                    $product->warehouseProduct()->delete();
                }
                $result   = $product->delete();
                if ($result && $old_image != '') {
                    $this->delete_file($old_image, PRODUCT_IMAGE_PATH);
                }
                $output   = $this->delete_message($result);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status ' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function generateProductCode()
    {
        $code = Keygen::numeric(8)->generate();
        if (DB::table('products')->where('product_code', $code)->exists()) {
            $this->generateProductCode();
        } else {
            return response()->json($code);
        }
    }
}
