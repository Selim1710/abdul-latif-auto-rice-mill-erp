<?php

namespace Modules\Production\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Category\Entities\Category;
use Modules\Production\Entities\Production;
use Modules\Production\Entities\ProductionProduct;
use Modules\Production\Http\Requests\ProductionProductFormRequest;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;

class ProductionProductController extends BaseController {
  private const pp = 'PRODUCTION-PRODUCT';
  public function __construct(ProductionProduct $model){
      return $this->model = $model;
  }
    public function create($id){
        if(permission('production-product-add')){
            $setTitle = __('file.Production Product');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $data = [
                'invoice_no' => self::pp.'-'.round(microtime(true)*1000),
                'production' => Production::findOrFail($id),
                'warehouses' => Warehouse::all(),
                'categories' => Category::all()
            ];
            return view('production::productionForm.stockCreate',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(ProductionProductFormRequest $request){
        if($request->ajax() && permission('production-product-add')){
            DB::beginTransaction();
            try{
                $productionProduct = [];
                $production        = Production::findOrFail($request->production_id);
                if($request->has('production_product')){
                    foreach ($request->production_product as $value){
                        if(!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['production_qty'])){
                            $productionProduct[] = [
                                'invoice_no'       => $request->invoice_no,
                                'warehouse_id'     => $value['warehouse_id'],
                                'product_id'       => $value['product_id'],
                                'qty'              => $value['qty'],
                                'scale'            => $value['scale'],
                                'production_qty'   => $value['production_qty'],
                                'price'            => $value['price'],
                                'sub_total'        => $value['sub_total'],
                                'use_warehouse_id' => $value['use_warehouse_id'],
                                'use_product_id'   => $value['use_product_id'],
                                'use_qty'          => $value['use_qty'],
                                'use_price'        => $value['use_price'],
                                'use_sub_total'    => $value['use_sub_total'],
                                'date'             => $request->date,
                            ];
                            $warehouseProduct       = WarehouseProduct::firstOrNew(
                                [
                                    'warehouse_id'  => $value['warehouse_id'],
                                    'product_id'    => $value['product_id']
                                ],
                                [
                                    'scale'         => $value['scale'],
                                    'qty'           => $value['production_qty']
                                ]
                            );
                            if(!empty($warehouseProduct)){
                                $warehouseProduct->update([
                                    'scale'          => $warehouseProduct->scale + $value['scale'],
                                    'qty'            => $warehouseProduct->qty + $value['production_qty'],
                                ]);
                            }
                            $warehouseProduct->save();
                            $useWarehouseProduct    = WarehouseProduct::firstWhere(['warehouse_id' => $value['use_warehouse_id'] , 'product_id' => $value['use_product_id']]);
                            if(empty($useWarehouseProduct)){
                                return response()->json(['status' => 'error' , 'message' => 'Product Is Empty']);
                            }
                            if($value['use_qty'] > $useWarehouseProduct->qty){
                                return response()->json(['status' => 'error' , 'message' => 'Use Quantity Can\'t Be Greater Then Warehouse Quantity']);
                            }
                            $useWarehouseProduct->update([
                                'scale' => $useWarehouseProduct->scale - $value['use_qty'],
                                'qty'   => $useWarehouseProduct->qty - $value['use_qty'],
                            ]);
                        }
                    }
                }
                $production->productionProduct()->attach($productionProduct);
                $output = ['status' => 'success','message' => $this->responseMessage('Data Saved')];
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                $output = ['status' => 'error' , 'message' => $e->getMessage()];
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function show($invoice_no){
        if(permission('production-product-details')){
            $setTitle = __('file.Production Product');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $products = $this->model->with('production','warehouse','product','product.unit')->where(['invoice_no' => $invoice_no])->get();
            return view('production::productionForm.stockDetails',compact('products'));
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function packing($invoice_no){
        if(permission('production-product-details')){
            $setTitle = __('file.Production Product');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $products = $this->model->with('production','warehouse','product','product.unit','useWarehouse','useProduct')->where(['invoice_no' => $invoice_no])->get();
            return view('production::productionForm.stockPacking',compact('products'));
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function gatePass($invoice_no){
        if(permission('production-product-details')){
            $setTitle = __('file.Production Product');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $products = $this->model->with('production','warehouse','product','product.unit')->where(['invoice_no' => $invoice_no])->get();
            return view('production::productionForm.stockGatePass',compact('products'));
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
