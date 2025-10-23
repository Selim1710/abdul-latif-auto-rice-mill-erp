<?php

namespace Modules\ChartOfHead\Entities;

use App\Models\BaseModel;
use Modules\Account\Entities\Transaction;

class ChartOfHead extends BaseModel {
    protected $fillable = ['master_head','type','head_id','sub_head_id','party_id','tenant_id','labor_head_id','bank_id','mobile_bank_id','mill_id','truck_id','expense_item_id','name','classification', 'employee_id'];
    protected $table    = 'chart_of_heads';
    public function head(){
        return $this->hasMany(Head::class,'master_head','id');
    }

    public function calculation($date,$id){
        $data = Transaction::where('created_at','LIKE','%'.$date.'%')->where('chart_of_head_id',$id)->get();
        $debit = 0 ; $credit = 0;
        foreach ($data as $value){
            if($value->debit == 0){
                $credit = $credit + $value->credit;
            }else{
                $debit  = $debit + $value->debit;
            }
        }
        $netBalance = $debit - $credit;
        return $netBalance;
    }
}
