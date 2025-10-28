<?php

namespace Modules\Purchase\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class PurchaseProduct extends BaseModel {
    protected $fillable  = ['purchase_id','warehouse_id','load_unload_rate','product_id','qty','scale','rec_qty','price','sub_total','receive_scale','receive_qty','return_scale','return_qty','purchase_date','note'];
    protected $table     = 'purchase_products';
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
