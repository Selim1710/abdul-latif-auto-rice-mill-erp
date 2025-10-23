<?php

namespace Modules\TenantProduction\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Expense\Entities\ExpenseItem;

class TenantProductionExpense extends BaseModel {
    protected $fillable = ['tenant_production_id','expense_item_id','expense_cost'];
    protected $table    = 'tenant_production_expenses';
    public function production() : BelongsTo {
        return $this->belongsTo(TenantProduction::class,'tenant_production_id','id');
    }
    public function expenseItem() : BelongsTo {
        return $this->belongsTo(ExpenseItem::class,'expense_item_id','id');
    }
}
