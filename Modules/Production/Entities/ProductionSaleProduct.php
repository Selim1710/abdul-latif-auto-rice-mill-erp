<?php

namespace Modules\Production\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class ProductionSaleProduct extends BaseModel {
    protected $fillable  = ['production_sale_id','product_id','qty','scale','sel_qty','price','sub_total','use_warehouse_id','use_product_id','use_qty','use_price','use_sub_total','date'];
    protected $table     = 'production_sale_products';
    public function productionSale(){
        return $this->belongsTo(ProductionSale::class,'production_sale_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function useWarehouse(){
        return $this->belongsTo(Warehouse::class,'use_warehouse_id','id');
    }
    public function useProduct(){
        return $this->belongsTo(Product::class,'use_product_id','id');
    }
}
