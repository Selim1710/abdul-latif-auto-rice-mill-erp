<?php

namespace Modules\Stock\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;

class StockTransferWarehouseProduct extends BaseModel {
    protected $fillable = ['stock_transfer_id','product_id','scale','qty', 'purchase_id','party_id','purchase_price'];
    protected $table    = 'stock_transfer_warehouse_products';
    public function stockTransfer(){
        return $this->belongsTo(StockTransfer::class,'stock_transfer_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function availableQty(int $warehouseId , int $productId){
        return WarehouseProduct::firstWhere(['warehouse_id' => $warehouseId , 'product_id' => $productId]);
    }
}
