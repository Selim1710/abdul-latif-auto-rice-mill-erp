<?php

namespace Modules\TenantProduction\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantProductionDelivery extends BaseModel {
    protected $fillable = ['tenant_production_id','invoice_no','date','total_delivery_qty','total_delivery_scale','created_by','modified_by'];
    protected $table    = 'tenant_production_deliveries';
    public function production() : BelongsTo {
        return $this->belongsTo(TenantProduction::class,'tenant_production_id','id');
    }
    public function deliveryProduct() : BelongsToMany {
        return $this->belongsToMany(TenantProductionDeliveryProduct::class,'tenant_production_delivery_products','t_p_d_id','product_id');
    }
    public function deliveryProductList() : HasMany {
        return $this->hasMany(TenantProductionDeliveryProduct::class,'t_p_d_id','id');
    }
    public function mergeDeliveryProductList() : HasMany {
        return $this->hasMany(TenantProductionMergeProduct::class,'invoice_no','invoice_no');
    }
}
