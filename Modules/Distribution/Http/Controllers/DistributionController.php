<?php

namespace Modules\Distribution\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Category\Entities\Category;
use Modules\Distribution\Entities\Distribution;
use Modules\Distribution\Http\Requests\DistributionRequestForm;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;

class DistributionController extends BaseController {
    private const distribution = 'DISTRIBUTION';
    public function __construct(Distribution $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('distribution-access')){
            $setTitle = __('file.Distribution');
            $this->setPageData($setTitle,$setTitle,'fas fa-tools',[['name' => $setTitle]]);
            return view('distribution::index');
        }else{
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request){
        if($request->ajax() && permission('distribution-access')){
            if (!empty($request->receiver_name)) {
                $this->model->setReceiverName($request->receiver_name);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('distribution-view')) {
                    $action .= ' <a class="dropdown-item view_data" href="'.route("distribution.show",$value->id).'">'.$this->actionButton('View').'</a>';
                }
                if(permission('distribution-edit') && $value->distribution_status == 2){
                    $action .= ' <a class="dropdown-item view_data" href="'.route("distribution.edit",$value->id).'">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('distribution-status-change') && $value->distribution_status == 2){
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->receiver_name . '" data-status = "1"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if(permission('distribution-delete') && $value->distribution_status == 2){
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->receiver_name . '">'.$this->actionButton('Delete').'</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->invoice_no;
                $row[]  = $value->date;
                $row[]  = $value->receiver_name;
                $row[]  = DISTRIBUTION_STATUS_LABEL[$value->distribution_status];
                $row[]  = $value->created_by;
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(), $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function create(){
        if(permission('distribution-add')){
            $setTitle = __('file.Add Distribution');
            $this->setPageData($setTitle,$setTitle,'fas fa-tools',[['name' => $setTitle]]);
            $data = [
                'invoice_no'   => self::distribution.'-'.round(microtime(true)*1000),
                'warehouses'   => Warehouse::all(),
                'categories'   => Category::all(),
            ];
            return view('distribution::create',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function store(DistributionRequestForm $request){
        if($request->ajax() && permission('distribution-add')){
            DB::beginTransaction();
            try{
                $distributionProduct = [];
                $collection          = collect($request->all())->except('_token','distribution')->merge(['created_by' => auth()->user()->name]);
                $distribution        = $this->model->create($collection->all());
                if($request->has('distribution')){
                    foreach ($request->distribution as $value){
                        if(!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['dis_qty'])){
                            $distributionProduct[]  = [
                                'warehouse_id'      => $value['warehouse_id'],
                                'product_id'        => $value['product_id'],
                                'qty'               => $value['qty'],
                                'scale'             => $value['scale'],
                                'dis_qty'           => $value['dis_qty'],
                                'date'              => $request->date,
                            ];
                        }
                    }
                }
                $distribution->distributionProduct()->attach($distributionProduct);
                $this->model->flushCache();
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
    public function show(int $id){
        if(permission('distribution-view')){
            $setTitle    = __('file.Distribution');
            $setSubTitle = __('file.Distribution Details');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-file',[['name'=>$setTitle,'link' => route('sale')],['name' => $setSubTitle]]);
            $distribution = $this->model->with('distributionProductList','distributionProductList.warehouse','distributionProductList.product','distributionProductList.product.unit')->findOrFail($id);
            return view('distribution::show',compact('distribution'));
        }else{
            return $this->access_blocked();
        }
    }
    public function edit(int $id){
        if(permission('distribution-edit')){
            $setTitle    = __('file.Distribution');
            $setSubTitle = __('file.Distribution Edit');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-file',[['name'=>$setTitle,'link' => route('sale')],['name' => $setSubTitle]]);
            $distribution =$this->model->with('distributionProductList','distributionProductList.warehouse','distributionProductList.product','distributionProductList.product.unit')->findOrFail($id);
            abort_if($distribution->distribution_status == 1,404);
            $data = [
                'warehouses'   => Warehouse::all(),
                'categories'   => Category::all(),
                'distribution' => $distribution
            ];
            return view('distribution::edit',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function update(DistributionRequestForm $request){
        if($request->ajax() && permission('distribution-edit')){
            DB::beginTransaction();
            try{
                $distributionProduct = [];
                $collection          = collect($request->all())->except('_token','distribution')->merge(['created_by' => auth()->user()->name]);
                $distribution        = $this->model->findOrFail($request->update_id);
                abort_if($distribution->distribution_status == 1,404);
                if($request->has('distribution')){
                    foreach ($request->distribution as $value){
                        if(!empty($value['warehouse_id']) && !empty($value['product_id']) && !empty($value['qty'])){
                            $distributionProduct[]  = [
                                'warehouse_id'      => $value['warehouse_id'],
                                'product_id'        => $value['product_id'],
                                'qty'               => $value['qty'],
                                'scale'             => $value['scale'],
                                'dis_qty'           => $value['dis_qty'],
                                'date'              => $request->date,
                            ];
                        }
                    }
                }
                $distribution->update($collection->all());
                $distribution->distributionProduct()->sync($distributionProduct);
                $distribution->touch();
                $this->model->flushCache();
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
        if($request->ajax() && permission('distribution-status-change')){
            DB::beginTransaction();
            try{
                $data = $this->model->with('distributionProductList')->findOrFail($request->id);
                abort_if($data->distribution_status == 1 , 404);
                foreach ($data->distributionProductList as $value){
                    $warehouseProduct  = WarehouseProduct::firstWhere(['warehouse_id' => $value['warehouse_id'],'product_id' => $value['product_id']]);
                    if( ($value->dis_qty > $warehouseProduct->qty) || ($value->scale > $warehouseProduct->scale) ){
                        return response()->json(['status' => 'error' , 'message' => 'Distribution Quantity Not Be Greater Then Stock Quantity']);
                    }
                    $warehouseProduct->update([
                        'scale'           => $warehouseProduct->scale - $value->scale,
                        'qty'             => $warehouseProduct->qty - $value->dis_qty,
                    ]);
                }
                $data->update([
                   'distribution_status' => $request->status
                ]);
                $this->model->flushCache();
                $output = ['status' => 'success' , 'message' => 'Data Status Change Successfully'];
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
        if($request->ajax() && permission('distribution-delete')){
            DB::beginTransaction();
            try{
                $distribution = $this->model->with('distributionProductList')->findOrFail($request->id);
                abort_if($distribution->distribution_status == 1,404);
                $distribution->distributionProductList()->delete();
                $distribution->delete();
                $this->model->flushCache();
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
    public function distributionCategoryProduct($categoryId){
        return Product::where(['category_id' => $categoryId])->get();
    }
    public function distributionProductDetails($warehouseId,$productId){
        $product          = Product::with('unit')->findOrFail($productId);
        $warehouseProduct = WarehouseProduct::firstWhere(['warehouse_id' => $warehouseId , 'product_id' => $productId]);
        return[
            'unitIdShow'  => $product->unit->unit_name.'('.$product->unit->unit_code.')',
            'unitId'      => $product->unit->unit_name,
            'availableQty'=> !empty($warehouseProduct) ? $warehouseProduct->qty : 0
        ];
    }
}
