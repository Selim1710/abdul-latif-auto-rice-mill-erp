<?php

namespace Modules\Production\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Transaction;
use Modules\Category\Entities\Category;
use Modules\ChartOfHead\Entities\ChartOfHead;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;
use Modules\Production\Entities\Production;
use Modules\Production\Entities\ProductionSale;
use Modules\Production\Http\Requests\ProductionSaleFormRequest;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;

class ProductionSaleController extends BaseController {
  private const ps = 'PRODUCTION-SALE';
  public function __construct(ProductionSale $model){
      return $this->model = $model;
  }
  public function create($id){
      if(permission('production-sale-add')){
          $setTitle = __('file.Production Sale');
          $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
          $data = [
            'invoice_no' => self::ps.'-'.round(microtime(true)*1000),
            'production' => Production::findOrFail($id),
            'parties'    => Party::all(),
            'warehouses' => Warehouse::all(),
            'categories' => Category::all()
          ];
          return view('production::productionForm.saleCreate',$data);
      }else{
          return $this->access_blocked();
      }
  }
  public function store(ProductionSaleFormRequest $request){
      if($request->ajax() && permission('production-sale-add')){
          DB::beginTransaction();
          try{
              $productionSale = [];
              $collection     = collect($request->all())->except('_token','production_sale')->merge(['created_by' => auth()->user()->name]);
              if($request->has('production_sale')){
                  foreach ($request->production_sale as $value){
                      if(!empty($value['product_id']) && !empty($value['qty']) && !empty($value['scale']) && !empty($value['sel_qty'])){
                          $productionSale[] = [
                              'product_id'       => $value['product_id'],
                              'qty'              => $value['qty'],
                              'scale'            => $value['scale'],
                              'sel_qty'          => $value['sel_qty'],
                              'price'            => $value['price'],
                              'sub_total'        => $value['sub_total'],
                              'use_warehouse_id' => $value['use_warehouse_id'],
                              'use_product_id'   => $value['use_product_id'],
                              'use_qty'          => $value['use_qty'],
                              'use_price'        => $value['use_price'],
                              'use_sub_total'    => $value['use_sub_total'],
                              'date'             => $request->sale_date,
                          ];
                          $warehouseProduct    = WarehouseProduct::firstWhere(['warehouse_id' => $value['use_warehouse_id'] , 'product_id' => $value['use_product_id']]);
                          if(empty($warehouseProduct)){
                              return response()->json(['status' => 'error' , 'message' => 'Product Is Empty']);
                          }
                          if($value['use_qty'] > $warehouseProduct->qty){
                              return response()->json(['status' => 'error' , 'message' => 'Use Quantity Can\'t Be Greater Then Warehouse Quantity']);
                          }
                          $warehouseProduct->update([
                              'scale' => $warehouseProduct->scale - $value['use_qty'],
                              'qty'   => $warehouseProduct->qty - $value['use_qty'],
                          ]);
                      }
                  }
              }
              $data   = $this->model->create($collection->all());
              $data->productionSale()->attach($productionSale);
              if($request->party_type == 1){
                  $party       = ChartOfHead::firstWhere(['master_head' => 1,'party_id' => $request->party_id]);
                  $cohId       = $party->id;
                  $name        = $party->name;
              }else{
                  $cohId       = 22;
                  $name        = 'Walking Party';
              }
              $narration = $name.' sale receive amount '.$request->total_sale_sub_total.' invoice no -'.$request->invoice_no;
              $this->balanceDebit($cohId,$request->invoice_no,$narration,$request->sale_date,abs($request->total_sale_sub_total));
              $this->balanceCredit(17,$request->invoice_no,$narration,$request->sale_date,abs($request->total_sale_sub_total));
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
        if(permission('production-sale-details')){
            $setTitle = __('file.Packing');
            $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
            $sale     = $this->model->with('party','productionSaleProductList','productionSaleProductList.product','productionSaleProductList.product.unit','productionSaleProductList.useWarehouse','productionSaleProductList.useProduct','productionSaleProductList.useProduct.unit')->findOrFail($id);
            return view('production::productionForm.salePacking',compact('sale'));
        }else{
            return response()->json($this->unauthorized());
        }
    }
  public function show($id){
      if(permission('production-sale-details')){
          $setTitle = __('file.Sale Details');
          $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
          $sale     = $this->model->with('party','productionSaleProductList','productionSaleProductList.product','productionSaleProductList.product.unit')->findOrFail($id);
          return view('production::productionForm.saleDetails',compact('sale'));
      }else{
          return response()->json($this->unauthorized());
      }
  }
  public function gatePass($id){
      if(permission('production-sale-details')){
          $setTitle = __('file.Gate Pass');
          $this->setPageData($setTitle,$setTitle,'fas fa-industry',[['name' => $setTitle]]);
          $sale     = $this->model->with('party','productionSaleProductList','productionSaleProductList.product','productionSaleProductList.product.unit')->findOrFail($id);
          return view('production::productionForm.saleGatePass',compact('sale'));
      }else{
          return response()->json($this->unauthorized());
      }
  }
    public function balanceDebit($cohId,$invoiceNo,$narration,$date,$paidAmount){
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => $date,
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => self::ps,
            'narration'        => $narration,
            'debit'            => $paidAmount,
            'credit'           => 0,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }
    public function balanceCredit($cohId,$invoiceNo,$narration,$date,$paidAmount){
        Transaction::create([
            'chart_of_head_id' => $cohId,
            'date'             => $date,
            'voucher_no'       => $invoiceNo,
            'voucher_type'     => self::ps,
            'narration'        => $narration,
            'debit'            => 0,
            'credit'           => $paidAmount,
            'status'           => 1,
            'is_opening'       => 2,
            'created_by'       => auth()->user()->name,
        ]);
    }
}
