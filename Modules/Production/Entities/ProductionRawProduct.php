<?php

namespace Modules\Production\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;

class ProductionRawProduct extends BaseModel {
    protected $fillable  = ['production_id','warehouse_id','product_id','price','qty','use_qty','rest_qty','scale','use_scale','scale_rest','pro_qty','use_pro_qty','rest_pro_qty','milling'];
    protected $table     = 'production_raw_products';
    public function production(){
        return $this->belongsToMany(Production::class,'production_id','id');
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function availableQty(int $warehouseId , int $productId){
        return WarehouseProduct::firstWhere(['warehouse_id' => $warehouseId , 'product_id' => $productId]);
    }
}
