<?php

namespace Modules\Tenant\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Entities\TenantReturnProduct;
use Modules\Tenant\Entities\TenantWarehouseProduct;
use Modules\Tenant\Http\Requests\TenantReturnProductFormRequest;

class TenantReturnProductController extends BaseController {
    private const trp = 'TENANT-RETURN-PRODUCT';
    public function __construct(TenantReturnProduct $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('tenant-return-access')){
            $setTitle = __('file.Tenant Return');
            $this->setPageData($setTitle,$setTitle,'fas fa-user-check',[['name'=>$setTitle]]);
            $data = [
                'tenants' => Tenant::all(),
            ];
            return view('tenant::tenantReturn.index',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('tenant-return-access')){
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
                if (permission('tenant-return-show')) {
                    $action .= ' <a class="dropdown-item" href="'.route("tenant.return.product.view",$value->id).'">'.$this->actionButton('Details').'</a>';
                }
                if (permission('tenant-return-edit') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item" href="'.route("tenant.return.product.edit",$value->id).'">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('tenant-return-status-change') && $value->status == 2){
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '" data-status="' . $value->status . '">'.$this->actionButton('Change Status').'</a>';
                }
                if (permission('tenant-return-delete') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->invoice_no;
                $row[]  = $value->tenant->name.'('.$value->tenant->mobile.')';
                $row[]  = $value->date;
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">'.$value->created_by.'</span>';
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function create(){
        if(permission('tenant-return-add')){
            $setTitle = __('file.Tenant Return');
            $this->setPageData($setTitle,$setTitle,'fas fa-user-check',[['name'=>$setTitle]]);
            $data = [
                'invoice_no'   => self::trp.'-'.round(microtime(true)*1000),
                'tenants'      => Tenant::all(),
                'warehouses'   => Warehouse::all(),
                'categories'   => Category::all(),
            ];
            return view('tenant::tenantReturn.create',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(TenantReturnProductFormRequest $request){
        if($request->ajax() && permission('tenant-return-add')){
            DB::beginTransaction();
            try{
                $tenantReturn = [];
                $collection   = collect($request->all())->except('_token','tenant_return')->merge(['status' => 2,'created_by' => auth()->user()->name]);
                $result       = $this->model->create($collection->all());
                if($request->has('tenant_return')){
                    foreach ($request->tenant_return as $value){
                        if(!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['ret_qty'])){
                            $tenantReturn[] = [
                                'warehouse_id' => $value['warehouse_id'],
                                'product_id'   => $value['product_id'],
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'ret_qty'      => $value['ret_qty']
                            ];
                        }
                    }
                }
                $result->tenantReturnProduct()->attach($tenantReturn);
                $output = ['status' => 'success' , 'message' => 'Data Saved Successfully'];
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
    public function show($id){
        if(permission('tenant-return-show')){
            $setTitle      = __('file.Tenant Return');
            $setSubTitle   = __('file.Tenant Return Details');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-file',[['name'=>$setTitle,'link' => route('tenant.return.product')],['name' => $setSubTitle]]);
            $tenantReturn = $this->model->with('tenant','tenantReturnProductList.warehouse','tenantReturnProductList.product','tenantReturnProductList.product.unit')->findOrFail($id);
            return view('tenant::tenantReturn.show',compact('tenantReturn'));
        }else{
            return $this->access_blocked();
        }
    }
    public function edit($id){
        if(permission('tenant-return-edit')){
            $setTitle = __('file.Tenant Return Edit');
            $this->setPageData($setTitle,$setTitle,'fas fa-user-check',[['name'=>$setTitle]]);
            $data = [
                'tenantReturn'   => $this->model->with('tenant','tenantReturnProductList.warehouse','tenantReturnProductList.product','tenantReturnProductList.product.unit')->findOrFail($id),
                'tenants'        => Tenant::all(),
                'warehouses'     => Warehouse::all(),
                'categories'     => Category::all(),
            ];
            return view('tenant::tenantReturn.edit',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function update(TenantReturnProductFormRequest $request){
        if($request->ajax() && permission('tenant-return-edit')){
            DB::beginTransaction();
            try{
                $tenantReturn = [];
                $collection   = collect($request->all())->except('_token','tenant_return')->merge(['status' => 2,'created_by' => auth()->user()->name]);
                $result       = $this->model->findOrFail($request->update_id);
                if($request->has('tenant_return')){
                    foreach ($request->tenant_return as $value){
                        if(!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['ret_qty'])){
                            $tenantReturn[Str::random(5)] = [
                                'warehouse_id' => $value['warehouse_id'],
                                'product_id'   => $value['product_id'],
                                'qty'          => $value['qty'],
                                'scale'        => $value['scale'],
                                'ret_qty'      => $value['ret_qty']
                            ];
                        }
                    }
                }
                $result->update($collection->all());
                $result->tenantReturnProduct()->sync($tenantReturn);
                $output = ['status' => 'success' , 'message' => 'Data Updated Successfully'];
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
    public function changeStatus(Request $request){
        if($request->ajax() && permission('tenant-return-status-change')){
            DB::beginTransaction();
            try{
                $tenantReturn = $this->model->with('tenantReturnProductList')->findOrFail($request->id);
                abort_if($tenantReturn->status == 1,404);
                foreach ($tenantReturn->tenantReturnProductList as $value){
                    $tenantWarehouseProduct = TenantWarehouseProduct::firstWhere(['tenant_id' => $tenantReturn->tenant_id ,'warehouse_id' => $value->warehouse_id , 'product_id' => $value->product_id , 'tenant_product_type' => 1]);
                    if ($value->ret_qty > $tenantWarehouseProduct->qty || $value->scale > $tenantWarehouseProduct->scale){
                        $output = ['status' => 'error' , 'message' => 'Product Quantity Or Scale Not Enough'];
                        return response()->json($output);
                    }
                    $tenantWarehouseProduct->update([
                        'qty'                 => $tenantWarehouseProduct->qty - $value->ret_qty,
                        'scale'               => $tenantWarehouseProduct->scale - $value->scale,
                    ]);
                }
                $tenantReturn->update([ 'status'  =>  1 ]);
                $output = ['status' => 'success' , 'message' => 'Status Change Successfully'];
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
    public function delete(Request $request){
        if($request->ajax() && permission('tenant-return-delete')){
            DB::beginTransaction();
            try{
                $tenantReturn = $this->model->with('tenantReturnProductList')->findOrFail($request->id);
                abort_if($tenantReturn->status == 1,404);
                $tenantReturn->tenantReturnProductList()->delete();
                $tenantReturn->delete();
                $output = ['status' => 'success' , 'message' => 'Data Deleted Successfully'];
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
    public function categoryProduct(Request $request){
        return Product::where(['category_id' => $request->categoryId])->get();
    }
    public function productDetails(Request $request){
        $warehouseProduct = TenantWarehouseProduct::with('product.unit')->firstWhere(['warehouse_id' => $request->warehouseId , 'product_id' => $request->productId ,'tenant_id' => $request->tenantId , 'tenant_product_type' => 1]);
        return[
            'unitId'      => !empty($warehouseProduct) ? $warehouseProduct->product->unit->unit_name : 0,
            'unitShow'    => !empty($warehouseProduct) ? $warehouseProduct->product->unit->unit_name.'('.$warehouseProduct->product->unit->unit_code.')' : 0,
            'availableQty'=> !empty($warehouseProduct) ? $warehouseProduct->qty : 0,
            'scale'       => !empty($warehouseProduct) ? $warehouseProduct->scale : 0,
        ];
    }
}
