<?php

namespace Modules\LaborHead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Setting\Entities\Warehouse;

class LaborBillDetail extends Model
{
    use HasFactory;

    protected $fillable = ['labor_bill_id', 'labor_bill_rate_detail_id', 'warehouse_id', 'rate', 'qty', 'amount'];
    
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
}
