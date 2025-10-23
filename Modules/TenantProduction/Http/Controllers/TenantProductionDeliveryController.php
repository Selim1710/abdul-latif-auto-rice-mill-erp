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
use Modules\TenantProduction\Entities\TenantProductionDelivery;
use Modules\TenantProduction\Http\Requests\TenantProductionDeliveryFormRequest;

class TenantProductionDeliveryController extends BaseController {
    private const tpd = 'TENANT-PRODUCTION-DELIVERY';
    public function __construct(TenantProductionDelivery $model){
        return $this->model = $model;
    }
    public function create($id){
        if(permission('tenant-production-delivery-add')){
            $setTitle = __('file.Tenant Production Delivery');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $data = [
                'invoice_no' => self::tpd.'-'.round(microtime(true)*1000),
                'production' => TenantProduction::with('tenant')->findOrFail($id),
                'warehouses' => Warehouse::all(),
                'categories' => Category::all()
            ];
            return view('tenantproduction::productionForm.deliveryCreate',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(TenantProductionDeliveryFormRequest $request){
        if($request->ajax() && permission('tenant-production-delivery-add')){
            DB::beginTransaction();
            try{
                $productDelivery  = [];
                $productMerge     = [];
                $tenantProduction = TenantProduction::findOrFail($request->tenant_production_id);
                $collection       = collect($request->all())->except('_token','production_delivery')->merge(['created_by' => auth()->user()->name]);
                if($request->has('production_delivery')){
                    foreach ($request->production_delivery as $value){
                        if(!empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['del_qty'])){
                            $productDelivery[] = [
                                'product_id'       => $value['product_id'],
                                'qty'              => $value['qty'],
                                'scale'            => $value['scale'],
                                'del_qty'          => $value['del_qty'],
                                'use_warehouse_id' => $value['use_warehouse_id'],
                                'use_product_id'   => $value['use_product_id'],
                                'use_qty'          => $value['use_qty'],
                                'date'             => $request->date,
                            ];
                            if(!empty($value['use_warehouse_id']) and !empty($value['use_product_id'])) {
                                $tenantWarehouseProduct = TenantWarehouseProduct::firstWhere(['tenant_id' => $request->tenant_id, 'warehouse_id' => $value['use_warehouse_id'], 'product_id' => $value['use_product_id']]);
                                if (empty($tenantWarehouseProduct)) {
                                    return response()->json(['status' => 'error', 'message' => 'Product Is Empty']);
                                }
                                if ($value['use_qty'] > $tenantWarehouseProduct->qty) {
                                    return response()->json(['status' => 'error', 'message' => 'Use Quantity Can\'t Be Greater Then Warehouse Quantity']);
                                }
                                $tenantWarehouseProduct->update([
                                    'scale' => $tenantWarehouseProduct->scale - $value['use_qty'],
                                    'qty' => $tenantWarehouseProduct->qty - $value['use_qty'],
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
                                'type'            => 2,
                            ];
                            if(!empty($value['merge_warehouse_id']) and !empty($value['merge_product_id'])) {
                                $warehouseProduct = WarehouseProduct::firstWhere(['warehouse_id' => $value['merge_warehouse_id'], 'product_id' => $value['merge_product_id']]);
                                if (empty($warehouseProduct)) {
                                    return response()->json(['status' => 'error', 'message' => 'Product Is Empty']);
                                }
                                if ($value['use_qty'] > $warehouseProduct->qty) {
                                    return response()->json(['status' => 'error', 'message' => 'Use Quantity Can\'t Be Greater Then Warehouse Quantity']);
                                }
                                $warehouseProduct->update([
                                    'scale' => $warehouseProduct->scale - $value['merge_qty'],
                                    'qty' => $warehouseProduct->qty - $value['merge_qty'],
                                ]);
                            }
                        }
                    }
                }
                $data   = $this->model->create($collection->all());
                $data->deliveryProduct()->attach($productDelivery);
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
    public function packing($id){
        if(permission('tenant-production-delivery-details')){
            $setTitle = __('file.Packing');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $delivery = $this->model->with('production','production.tenant','deliveryProductList.product.unit','deliveryProductList.useWarehouse','deliveryProductList.useProduct.category','deliveryProductList.useProduct.unit','mergeDeliveryProductList','mergeDeliveryProductList.warehouse','mergeDeliveryProductList.product.unit','mergeDeliveryProductList.product.category')->findOrFail($id);
            return view('tenantproduction::productionForm.deliveryPacking',compact('delivery'));
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function show($id){
        if(permission('tenant-production-delivery-details')){
            $setTitle = __('file.Tenant Delivery Details');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $delivery = $this->model->with('production','production.tenant','deliveryProductList.product.unit')->findOrFail($id);
            return view('tenantproduction::productionForm.deliveryDetails',compact('delivery'));
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
