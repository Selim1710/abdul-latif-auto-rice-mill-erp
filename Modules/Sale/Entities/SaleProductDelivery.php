<?php

namespace Modules\Sale\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class SaleProductDelivery extends BaseModel {
    protected $fillable = ['sale_id','invoice_no','warehouse_id','product_id','price','scale','qty','date'];
    protected $table    = 'sale_product_deliveries';
    public function sale(){
        return $this->belongsTo(Sale::class,'sale_id','id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
