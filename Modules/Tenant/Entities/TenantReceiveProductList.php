<?php

namespace Modules\Tenant\Entities;

use App\Models\BaseModel;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class TenantReceiveProductList extends BaseModel {
    protected $fillable = ['tenant_receive_id','warehouse_id','product_id','qty','scale','rec_qty','load_unload_rate','load_unload_amount'];
    protected $table    = 'tenant_receive_product_lists';
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
