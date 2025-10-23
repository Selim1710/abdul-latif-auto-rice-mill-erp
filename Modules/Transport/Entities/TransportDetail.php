<?php

namespace Modules\Transport\Entities;

use App\Models\BaseModel;
use Modules\Expense\Entities\ExpenseItem;

class TransportDetail extends BaseModel{
    protected $fillable = ['transport_id', 'income_name', 'income_value', 'expense_category_id', 'expense_value'];
    protected $table    = 'transport_details';
    public function expenseItem(){
        return $this->belongsTo(ExpenseItem::class,'expense_item_id','id');
    }
}
