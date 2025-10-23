<?php

namespace Modules\TenantProduction\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class TenantProductionProduct extends BaseModel {
    protected $fillable = ['invoice_no','date','tenant_production_id','warehouse_id','product_id','qty','scale','production_qty','use_warehouse_id','use_product_id','use_qty'];
    protected $table    = 'tenant_production_products';
    public function production() : BelongsTo {
        return $this->belongsTo(TenantProduction::class,'tenant_production_id','id');
    }
    public function warehouse() : BelongsTo {
        return $this->belongsTo(Warehouse::class ,'warehouse_id','id');
    }
    public function product() : BelongsTo {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function useWarehouse() : BelongsTo {
        return $this->belongsTo(Warehouse::class,'use_warehouse_id','id');
    }
    public function useProduct() : BelongsTo {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function mergeProductionProductList() : HasMany {
        return $this->hasMany(TenantProductionMergeProduct::class,'invoice_no','invoice_no');
    }
}
