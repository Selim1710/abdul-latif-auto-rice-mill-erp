<?php

namespace Modules\Report\Entities;

use App\Models\BaseModel;

class Due extends BaseModel {
    protected $fillable = ['chart_of_head_id','date','voucher_no','voucher_type','narration','debit','credit','status','is_opening','created_by','modified_by'];
    protected $table    = 'transactions';
}
