<?php

namespace Modules\Sale\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\WarehouseProduct;

class SaleProduct extends BaseModel {
    protected $fillable = ['sale_id', 'warehouse_id','load_unload_rate', 'product_id','qty','scale','sel_qty', 'price','sub_total','delivery_scale','delivery_qty','return_scale','return_qty','sale_date','note'];
    public function sale(){
        return $this->belongsTo(Sale::class,'sale_id','id');
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
