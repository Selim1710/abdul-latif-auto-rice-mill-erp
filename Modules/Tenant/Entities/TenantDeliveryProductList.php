<?php

namespace Modules\Tenant\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class TenantDeliveryProductList extends BaseModel {
    protected $fillable = ['tenant_delivery_product_id','warehouse_id','product_id','qty','scale','del_qty'];
    protected $table    = 'tenant_delivery_product_lists';
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function availableQty(int $warehouseId , int $productId){
        return TenantWarehouseProduct::with('product.unit')->firstWhere(['warehouse_id' => $warehouseId , 'product_id' => $productId, 'tenant_product_type' => 2]);
    }
}
