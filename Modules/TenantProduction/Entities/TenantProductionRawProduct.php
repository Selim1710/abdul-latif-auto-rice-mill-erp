<?php

namespace Modules\TenantProduction\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;
use Modules\Tenant\Entities\TenantWarehouseProduct;

class TenantProductionRawProduct extends BaseModel {
    protected $fillable  = ['tenant_production_id','date','warehouse_id','product_id','qty','use_qty','scale','use_scale','pro_qty','use_pro_qty','milling'];
    protected $table     = 'tenant_production_raw_products';
    public function production() : BelongsTo {
        return $this->belongsTo(TenantProductionRawProduct::class,'tenant_production_id','id');
    }
    public function warehouse() : BelongsTo {
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product() : BelongsTo {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function availableQty(int $tenantId, int $warehouseId , int $productId){
        return TenantWarehouseProduct::firstWhere(['tenant_id' => $tenantId,'warehouse_id' => $warehouseId , 'product_id' => $productId]);
    }
}
