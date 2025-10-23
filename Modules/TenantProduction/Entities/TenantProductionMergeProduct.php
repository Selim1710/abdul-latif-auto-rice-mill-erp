<?php

namespace Modules\TenantProduction\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class TenantProductionMergeProduct extends BaseModel {
    protected $fillable  = ['tenant_production_id','invoice_no','date','warehouse_id','product_id','price','qty','scale','mer_qty','sub_total','milling','type'];
    protected $table     = 'tenant_production_merge_products';
    public function production() : BelongsTo {
        return $this->belongsTo(TenantProduction::class,'tenant_production_id','id');
    }
    public function warehouse() : BelongsTo {
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product() : BelongsTo {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
