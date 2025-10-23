<?php

namespace Modules\Production\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class ProductionProduct extends BaseModel {
    protected $fillable  = ['invoice_no','production_id','warehouse_id','product_id','qty','scale','production_qty','price','sub_total','use_warehouse_id','use_product_id','use_qty','use_price','use_sub_total','date'];
    protected $table     = 'production_products';
    public function production(){
        return $this->belongsTo(Production::class,'production_id','id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
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
