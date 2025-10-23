<?php

namespace Modules\Account\Entities;

use App\Models\BaseModel;
use Modules\ChartOfHead\Entities\ChartOfHead;

class Transaction extends BaseModel{
    protected $fillable = ['chart_of_head_id','date','voucher_no','voucher_type','narration','debit','credit','status','is_opening','created_by','modified_by'];
    protected $table    = 'transactions';
    public function coh(){
        return $this->belongsTo(ChartOfHead::class,'chart_of_head_id','id');
    }
}
