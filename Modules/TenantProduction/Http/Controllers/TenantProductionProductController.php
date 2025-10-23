<?php

namespace Modules\TenantProduction\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Category\Entities\Category;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;
use Modules\Tenant\Entities\TenantWarehouseProduct;
use Modules\TenantProduction\Entities\TenantProduction;
use Modules\TenantProduction\Entities\TenantProductionProduct;
use Modules\TenantProduction\Http\Requests\TenantProductionProductFormRequest;

class TenantProductionProductController extends BaseController {
  private const tpp = 'TENANT-PRODUCTION-PRODUCT';
  public function __construct(TenantProductionProduct $model){
      return $this->model = $model;
  }
    public function create($id){
        if(permission('tenant-production-product-add')){
            $setTitle = __('file.Tenant Production Product');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $data     = [
                'invoice_no' => self::tpp.'-'.round(microtime(true)*1000),
                'production' => TenantProduction::with('tenant')->findOrFail($id),
                'warehouses' => Warehouse::all(),
                'categories' => Category::all()
            ];
            return view('tenantproduction::productionForm.stockCreate',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(TenantProductionProductFormRequest $request){
        if($request->ajax() && permission('tenant-production-product-add')){
            DB::beginTransaction();
            try{
                $productProduct   = [];
                $productMerge     = [];
                $tenantProduction = TenantProduction::findOrFail($request->tenant_production_id);
                if($request->has('production_product')){
                    foreach ($request->production_product as $value){
                        if(!empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['production_qty'])){
                            $productProduct[] = [
                                'invoice_no'       => $request->invoice_no,
                                'date'             => $request->date,
                                'warehouse_id'     => $value['warehouse_id'],
                                'product_id'       => $value['product_id'],
                                'qty'              => $value['qty'],
                                'scale'            => $value['scale'],
                                'production_qty'   => $value['production_qty'],
                                'use_warehouse_id' => $value['use_warehouse_id'],
                                'use_product_id'   => $value['use_product_id'],
                                'use_qty'          => $value['use_qty'],
                            ];
                            $tenantWarehouseProductionProduct = TenantWarehouseProduct::firstOrNew(
                                [
                                    'tenant_id'            => $request->tenant_id,
                                    'warehouse_id'         => $value['warehouse_id'],
                                    'product_id'           => $value['product_id'],
                                    'tenant_product_type'  => 2
                                ],
                                [
                                    'scale'         => $value['scale'],
                                    'qty'           => $value['production_qty']
                                ]
                            );
                            if(!empty($tenantWarehouseProductionProduct)){
                                $tenantWarehouseProductionProduct->update([
                                    'scale'          => $tenantWarehouseProductionProduct->scale + $value['scale'],
                                    'qty'            => $tenantWarehouseProductionProduct->qty + $value['production_qty'],
                                ]);
                            }
                            $tenantWarehouseProductionProduct->save();
                            if(!empty($value['use_warehouse_id']) and !empty($value['use_product_id'])){
                                $tenantWarehouseProduct  = TenantWarehouseProduct::firstWhere(['tenant_id' => $request->tenant_id,'warehouse_id' => $value['use_warehouse_id'] , 'product_id' => $value['use_product_id']]);
                                if(empty($tenantWarehouseProduct)){
                                    return response()->json(['status' => 'error' , 'message' => 'Product Is Empty']);
                                }
                                if($value['use_qty'] > $tenantWarehouseProduct->qty){
                                    return response()->json(['status' => 'error' , 'message' => 'Use Quantity Can\'t Be Greater Then Warehouse Quantity']);
                                }
                                $tenantWarehouseProduct->update([
                                    'scale' => $tenantWarehouseProduct->scale - $value['use_qty'],
                                    'qty'   => $tenantWarehouseProduct->qty - $value['use_qty'],
                                ]);
                            }
                        }
                        if(!empty($value['merge_warehouse_id']) && !empty($value['merge_product_id']) && !empty($value['merge_qty']) && !empty($value['merge_price'])){
                            $productMerge[] = [
                                'invoice_no'      => $request->invoice_no,
                                'date'            => $request->date,
                                'warehouse_id'    => $value['merge_warehouse_id'],
                                'product_id'      => $value['merge_product_id'],
                                'price'           => $value['merge_price'],
                                'qty'             => $value['merge_qty'],
                                'scale'           => $value['merge_qty'],
                                'mer_qty'         => $value['merge_qty'],
                                'sub_total'       => $value['merge_sub_total'],
                                'type'            => 3,
                            ];
                            if(!empty($value['merge_warehouse_id']) and !empty($value['merge_product_id'])){
                                $warehouseProduct    = WarehouseProduct::firstWhere(['warehouse_id' => $value['merge_warehouse_id'] , 'product_id' => $value['merge_product_id']]);
                                if(empty($warehouseProduct)){
                                    return response()->json(['status' => 'error' , 'message' => 'Product Is Empty']);
                                }
                                if($value['use_qty'] > $warehouseProduct->qty){
                                    return response()->json(['status' => 'error' , 'message' => 'Use Quantity Can\'t Be Greater Then Warehouse Quantity']);
                                }
                                $warehouseProduct->update([
                                    'scale' => $warehouseProduct->scale - $value['merge_qty'],
                                    'qty'   => $warehouseProduct->qty - $value['merge_qty'],
                                ]);
                            }
                        }
                    }
                }
                $tenantProduction->product()->attach($productProduct);
                $tenantProduction->mergeProduct()->attach($productMerge);
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
    public function packing($invoice_no){
        if(permission('tenant-production-product-details')){
            $setTitle = __('file.Tenant Production Product');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $products = $this->model->with('production','warehouse','product','product.unit','useWarehouse','useProduct','mergeProductionProductList.warehouse','mergeProductionProductList.product.unit','mergeProductionProductList.product.category')->where(['invoice_no' => $invoice_no])->get();
            return view('tenantproduction::productionForm.stockPacking',compact('products'));
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function show($invoice_no){
        if(permission('tenant-production-product-details')){
            $setTitle = __('file.Tenant Production Product');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $products = $this->model->with('production','warehouse','product','product.unit')->where(['invoice_no' => $invoice_no])->get();
            return view('tenantproduction::productionForm.stockDetails',compact('products'));
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
