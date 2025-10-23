<?php

namespace Modules\TenantProduction\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class TenantProductionDeliveryProduct extends BaseModel {
    protected $fillable = ['date','t_p_d_id','product_id','qty','scale','del_qty','use_warehouse_id','use_product_id','use_qty'];
    protected $table    = 'tenant_production_delivery_products';
    public function delivery() : BelongsTo {
        return $this->belongsTo(TenantProductionDelivery::class,'t_p_d_id','id');
    }
    public function product() : BelongsTo {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function useWarehouse() : BelongsTo {
        return $this->belongsTo(Warehouse::class,'use_warehouse_id','id');
    }
    public function useProduct() : BelongsTo {
        return $this->belongsTo(Product::class,'use_product_id','id');
    }
}
