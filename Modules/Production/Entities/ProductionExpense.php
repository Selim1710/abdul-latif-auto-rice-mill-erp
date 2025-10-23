<?php

namespace Modules\Production\Entities;

use App\Models\BaseModel;
use Modules\Expense\Entities\ExpenseItem;

class ProductionExpense extends BaseModel {
    protected $fillable = ['production_id','expense_item_id','expense_cost'];
    protected $table    = 'production_expenses';
    public function production(){
        return $this->belongsTo(Production::class,'production_id','id');
    }
    public function expenseItem(){
        return $this->belongsTo(ExpenseItem::class,'expense_item_id','id');
    }
}
