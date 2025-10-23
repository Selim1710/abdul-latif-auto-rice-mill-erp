<?php

namespace Modules\Distribution\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;

class DistributionProduct extends BaseModel {
    protected $fillable = ['distribution_id','warehouse_id','product_id','qty','scale','dis_qty','date'];
    protected $table    = 'distribution_products';
    public function distribution(){
        return $this->belongsTo(Distribution::class,'distribution_id','id');
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
