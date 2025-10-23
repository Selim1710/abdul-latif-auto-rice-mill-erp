<?php

namespace Modules\Expense\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;

class ExpenseItem extends BaseModel{
    protected $fillable = ['name','status','expense_type', 'created_by', 'modified_by'];
    protected $table    = 'expense_items';
    protected $_name;
    public function setName($name){
        $this->_name = $name;
    }
    public function scopeProductionExpense($query){
        return $query->where(['status' => 1,'expense_type' => 1]);
    }
    public function scopeOtherExpense($query){
        return $query->where(['status' => 1,'expense_type' => 2]);
    }
    private function get_datatable_query(){
        $this->column_order = ['name','status','expense_type', 'created_by', 'modified_by',null];
        $query = self::toBase();
        if (!empty($this->_name)) {
            $query->where('name', 'like', '%' . $this->_name . '%');
        }
        if (isset($this->orderValue) && isset($this->dirValue)) { //orderValue is the index number of table header and dirValue is asc or desc
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue); //fetch data order by matching column
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }
    public function getDatatableList(){
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }
    public function count_filtered(){
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }
    public function count_all(){
        return self::toBase()->get()->count();
    }
    protected const ALL_EXPENSE_ITEMS       = '_expense_items';
    protected const ACTIVE_EXPENSE_ITEMS    = '_active_expense_items';
    public static function allExpenseItems(){
        return Cache::rememberForever(self::ALL_EXPENSE_ITEMS, function () {
            return self::toBase()->get();
        });
    }
    public static function activeExpenseItems(){
        return Cache::rememberForever(self::ACTIVE_EXPENSE_ITEMS, function () {
            return self::toBase()->where('status',1)->get();
        });
    }
    public static function flushCache(){
        Cache::forget(self::ALL_EXPENSE_ITEMS);
        Cache::forget(self::ACTIVE_EXPENSE_ITEMS);
    }
    public static function boot(){
        parent::boot();
        static::updated(function () {
            self::flushCache();
        });
        static::created(function() {
            self::flushCache();
        });
        static::deleted(function() {
            self::flushCache();
        });
    }
}
