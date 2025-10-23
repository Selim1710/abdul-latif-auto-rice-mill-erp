<?php

namespace Modules\Stock\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Category\Entities\Category;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class ProductLedgerController extends BaseController{
    public function index(){
        if(permission('product-ledger-access')){
            $title   = __('file.Product Ledger');
            $this->setPageData($title,$title,'fas fa-file',[['name' => $title]]);
            $data = [
              'products'  => Product::all(),
              'categories'=> Category::all(),
              'parties'   => Party::all(),
              'warehouses'=> Warehouse::all(),
            ];
            return view('stock::productLedger.index',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function productLedgerData(Request $request){
        $products = '';
        $table    = '';
        switch($request->product_ledger){
            case(1) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('sale_products as salPro','salPro.product_id','=','pro.id')
                            ->join('sales as sal','salPro.sale_id','=','sal.id')
                            ->join('parties as par','sal.party_id','=','par.id')
                            ->join('warehouses as war','salPro.warehouse_id','=','war.id')
                            ->where([['sal.party_type','=',1],['sal.sale_status','=',4]])
                            ->whereBetween('sal.sale_date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->when(request('party_id'),function($q3){
                                $q3->where('sal.party_id','=',request('party_id'));
                            })
                            ->when(request('warehouse_id'),function($q4){
                                $q4->where('salPro.warehouse_id','=',request('warehouse_id'));
                            })
                            ->select('sal.invoice_no as invoiceNo','sal.sale_date as date','pro.product_name as name',
                                             'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                             'salPro.scale as scale','salPro.sel_qty as qty','salPro.price as price','salPro.sub_total as subTotal')
                            ->get();
                break;
            case(2) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('sale_products as salPro','salPro.product_id','=','pro.id')
                            ->join('sales as sal','salPro.sale_id','=','sal.id')
                            ->join('warehouses as war','salPro.warehouse_id','=','war.id')
                            ->where([['sal.party_type','=',2],['sal.sale_status','=',4]])
                            ->whereBetween('sal.sale_date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->when(request('warehouse_id'),function($q4){
                                $q4->where('salPro.warehouse_id','=',request('warehouse_id'));
                            })
                            ->select('sal.invoice_no as invoiceNo','sal.sale_date as date','pro.product_name as name',
                                'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                'salPro.scale as scale','salPro.sel_qty as qty','salPro.price as price','salPro.sub_total as subTotal')
                            ->get();
                break;
            case(3) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('sale_products as salPro','salPro.product_id','=','pro.id')
                            ->join('sales as sal','salPro.sale_id','=','sal.id')
                            ->join('warehouses as war','salPro.warehouse_id','=','war.id')
                            ->where([['sal.sale_status','=',4]])
                            ->whereBetween('sal.sale_date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->when(request('warehouse_id'),function($q4){
                                $q4->where('salPro.warehouse_id','=',request('warehouse_id'));
                            })
                            ->select('sal.invoice_no as invoiceNo','sal.sale_date as date','pro.product_name as name',
                                'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                'salPro.scale as scale','salPro.sel_qty as qty','salPro.price as price','salPro.sub_total as subTotal')
                            ->get();
                break;
            case(4) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('purchase_products as purPro','purPro.product_id','=','pro.id')
                            ->join('purchases as pur','purPro.purchase_id','=','pur.id')
                            ->join('parties as par','pur.party_id','=','par.id')
                            ->join('warehouses as war','purPro.warehouse_id','=','war.id')
                            ->where([['pur.party_type','=',1],['pur.purchase_status','=',4]])
                            ->whereBetween('pur.purchase_date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->when(request('party_id'),function($q3){
                                $q3->where('pur.party_id','=',request('party_id'));
                            })
                            ->when(request('warehouse_id'),function($q4){
                                $q4->where('purPro.warehouse_id','=',request('warehouse_id'));
                            })
                            ->select('pur.invoice_no as invoiceNo','pur.purchase_date as date','pro.product_name as name',
                                'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                'purPro.scale as scale','purPro.rec_qty as qty','purPro.price as price','purPro.sub_total as subTotal')
                            ->get();
                break;
            case(5) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('purchase_products as purPro','purPro.product_id','=','pro.id')
                            ->join('purchases as pur','purPro.purchase_id','=','pur.id')
                            ->join('warehouses as war','purPro.warehouse_id','=','war.id')
                            ->where([['pur.party_type','=',2],['pur.purchase_status','=',4]])
                            ->whereBetween('pur.purchase_date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->when(request('warehouse_id'),function($q4){
                                $q4->where('purPro.warehouse_id','=',request('warehouse_id'));
                            })
                            ->select('pur.invoice_no as invoiceNo','pur.purchase_date as date','pro.product_name as name',
                                'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                'purPro.scale as scale','purPro.rec_qty as qty','purPro.price as price','purPro.sub_total as subTotal')
                            ->get();
                break;
            case(6) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('purchase_products as purPro','purPro.product_id','=','pro.id')
                            ->join('purchases as pur','purPro.purchase_id','=','pur.id')
                            ->join('warehouses as war','purPro.warehouse_id','=','war.id')
                            ->where([['pur.purchase_status','=',4]])
                            ->whereBetween('pur.purchase_date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->when(request('warehouse_id'),function($q4){
                                $q4->where('purPro.warehouse_id','=',request('warehouse_id'));
                            })
                            ->select('pur.invoice_no as invoiceNo','pur.purchase_date as date','pro.product_name as name',
                                'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                'purPro.scale as scale','purPro.rec_qty as qty','purPro.price as price','purPro.sub_total as subTotal')
                            ->get();
                break;
            case(7) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('production_raw_products as prodRawPro','prodRawPro.product_id','=','pro.id')
                            ->join('productions as prod','prodRawPro.production_id','=','prod.id')
                            ->join('warehouses as war','prodRawPro.warehouse_id','=','war.id')
                            ->where([['prod.production_status','=',4]])
                            ->whereBetween('prod.date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->when(request('warehouse_id'),function($q4){
                                $q4->where('prodRawPro.warehouse_id','=',request('warehouse_id'));
                            })
                            ->select('prod.invoice_no as invoiceNo','prod.date as date','pro.product_name as name',
                                'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                'prodRawPro.use_scale as scale','prodRawPro.use_qty as qty','prodRawPro.price as price',
                                DB::raw('(prodRawPro.use_qty * prodRawPro.price) as subTotal'))
                            ->get();
                break;
            case(8) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('production_products as prodPro','prodPro.product_id','=','pro.id')
                            ->join('productions as prod','prodPro.production_id','=','prod.id')
                            ->join('warehouses as war','prodPro.warehouse_id','=','war.id')
                            ->whereBetween('prod.date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->when(request('warehouse_id'),function($q4){
                                $q4->where('prodPro.warehouse_id','=',request('warehouse_id'));
                            })
                            ->select('prod.invoice_no as invoiceNo','prod.date as date','pro.product_name as name',
                                'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                'prodPro.scale as scale','prodPro.production_qty as qty','prodPro.price as price',
                                DB::raw('(prodPro.production_qty * prodPro.price) as subTotal'))
                            ->get();
                break;
            case(9) :
                $products = DB::table('products as pro')
                            ->join('units as uni','pro.unit_id','=','uni.id')
                            ->join('categories as cat','pro.category_id','=','cat.id')
                            ->join('production_sale_products as prodSalPro','prodSalPro.product_id','=','pro.id')
                            ->join('production_sales as prodSal','prodSalPro.production_sale_id','=','prodSal.id')
                            ->join('productions as prod','prodSal.production_id','=','prod.id')
                            ->whereBetween('prod.date',[$request->start_date,$request->end_date])
                            ->when(request('product_id'),function($q1){
                                $q1->where('pro.id','=',request('product_id'));
                            })
                            ->when(request('category_id'),function($q2){
                                $q2->where('pro.category_id','=',request('category_id'));
                            })
                            ->select('prod.invoice_no as invoiceNo','prod.date as date','pro.product_name as name',
                                'uni.unit_name as unit','uni.unit_code as code','cat.category_name as category',
                                'prodSalPro.scale as scale','prodSalPro.sel_qty as qty','prodSalPro.price as price','prodSalPro.sub_total as subTotal')
                            ->get();
                break;
        }
        $table      .= '<table style="margin-bottom:10px !important;">
                            <tr>
                                <td class="text-center">
                                    <h1 class="name m-0 head_title" style="text-transform: uppercase"><b>'.(config('settings.title') ? config('settings.title') : env('APP_NAME')).'</b></h1>
                                    <h3 class="name m-0 head_address"><b>'.(config('settings.address') ?  config('settings.address') : env('APP_NAME')).'</b></h3>
                                    <h3 class="name m-0 head_contact_no"><b>'.(config('settings.contact_no') ? "Contact No : ". config('settings.contact_no') : env('APP_NAME')).'</b></h3>
                                    <h3 class="name m-0 head_email"><b>'.(config('settings.email') ? "Email : ".config('settings.email') : env('APP_NAME')).'</b></h3>
                                    <p style="font-weight: normal;font-weight:bold;margin: 10px auto 5px auto;font-weight: bold;background: gray;width: 250px;color: white;text-align: center;padding:5px 0;}">'.__('file.Product Ledger').'</p>
                                    <p style="font-weight: normal;margin:0;font-weight:bold;">'.__('file.Date').': '.date('d-m-Y',strtotime($request->start_date)).' '.__('file.To').' '.date('d-m-Y',strtotime($request->end_date)).'</p>
                                </td>
                            </tr>
                        </table>';
        $table      .= '<table style="margin-bottom:10px !important;">
                            <thead>
                                <tr class="text-center">
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Invoice No').'</button></th>
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Date').'</button></th>
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Product').'</button></th>
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Unit').'</button></th>
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Category').'</button></th>
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Scale').'</button></th>
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Qty').'</button></th>
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Price').'</button></th>
                                    <th><button type="button" class="btn btn-block btn-primary">'.__('file.Sub Total').'</button></th>
                                </tr>
                            </thead>';
        $table      .= '<tbody>';
        foreach ($products as $product){
            $table  .= '<tr class="text-center">
                          <td class="no">'.$product->invoiceNo.'</td>
                          <td class="no">'.date('d-M-Y',strtotime($product->date)).'</td>
                          <td class="no">'.$product->name.'</td>
                          <td class="no">'.$product->unit.'('.$product->code.')'.'</td>
                          <td class="no"><span class="label label-primary label-pill label-inline" style="min-width:100px !important;">'.$product->category.'</span></td>
                          <td class="no">'.number_format($product->scale,2).'</td>
                          <td class="no">'.number_format($product->qty,2).'</td>
                          <td class="no">'.number_format($product->price,2).'</td>
                          <td class="no">'.number_format($product->subTotal,2).'</td>
                        </tr>';
        }
        $table   .= '</tbody></table>';
        $table   .= '<table>
                        <tr class="text-center">
                           <td class="no"><button type="button" class="btn btn-block btn-primary">'.__('file.Scale').'</button></td>
                           <td class="no">'.number_format(array_sum(array_column($products->toArray(), 'scale'))).'</td>
                         </tr>
                         <tr class="text-center">
                           <td class="no"><button type="button" class="btn btn-block btn-primary">'.__('file.Qty').'</button></td>
                           <td class="no">'.number_format(array_sum(array_column($products->toArray(), 'qty'))).'</td>
                         </tr>
                         <tr class="text-center">
                           <td class="no"><button type="button" class="btn btn-block btn-primary">'.__('file.Sub Total').'</button></td>
                           <td class="no">'.number_format(array_sum(array_column($products->toArray(), 'subTotal'))).'</td>
                         </tr>';
        $table   .= '</table>';
        return $table;
    }
}
