<?php

namespace Modules\Stock\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class OpeningStock extends BaseModel {
    protected $fillable = ['warehouse_id','product_id','scale','qty'];
    protected $table    = 'opening_stocks';
    public function warehouse() : BelongsTo{
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product() : BelongsTo {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
