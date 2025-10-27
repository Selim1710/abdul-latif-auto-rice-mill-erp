<?php

namespace Modules\LaborHead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Setting\Entities\Warehouse;

class LaborBillRateDetail extends Model
{
    use HasFactory;

    protected $fillable = ['labor_bill_rate_id', 'warehouse_id', 'rate'];

     
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

}
