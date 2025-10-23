<?php

namespace Modules\Purchase\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class PurchaseProductReturn extends BaseModel {
    protected $fillable  = ['purchase_id','invoice_no','warehouse_id','product_id','price','scale','qty','date'];
    protected $table     = 'purchase_product_returns';
    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
