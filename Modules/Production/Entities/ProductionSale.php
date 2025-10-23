<?php

namespace Modules\Production\Entities;

use App\Models\BaseModel;
use Modules\Party\Entities\Party;

class ProductionSale extends BaseModel {
    protected $fillable  = ['production_id','invoice_no','sale_date','party_type','party_id','party_name','document','discount','total_sale_qty','total_sale_scale','total_sale_sub_total','previous_due','net_total','paid_amount','due_amount','payment_status','payment_method','account_id','created_by','modified_by'];
    protected $table     = 'production_sales';
    public function party(){
        return $this->belongsTo(Party::class,'party_id','id');
    }
    public function productionSale(){
        return $this->belongsToMany(ProductionSaleProduct::class,'production_sale_products','production_sale_id','product_id')->withTimestamps();
    }
    public function productionSaleProductList(){
        return $this->hasMany(ProductionSaleProduct::class,'production_sale_id','id');
    }
}
