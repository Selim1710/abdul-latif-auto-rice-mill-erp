<?php

namespace Modules\LaborHead\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class LaborBillRate extends BaseModel {
    protected $fillable                     = ['name','rate','status','created_by'];
    protected $table                        = 'labor_bill_rates';
    protected const ALL_LABOR_BILL_RATES    = '_all_labor_bill_rates';
    protected $_name;
    public function setName($name){
        $this->_name = $name;
    }
    public function laborBill() : HasMany{
        return $this->hasMany(LaborBill::class,'labor_bill_rate_id','id');
    }
    private function get_datatable_query(){
        $this->column_order = ['id','name','rate','status','created_by',null];
        $query              = self::toBase();
        if (!empty($this->_name)) {
            $query->where('name', 'like', '%' . $this->_name . '%');
        }
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
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
    public static function allLaborBillRateList(){
        return Cache::rememberForever(self::ALL_LABOR_BILL_RATES, function () {
            return self::toBase()->latest()->get();
        });
    }
    public static function flushCache(){
        Cache::forget(self::ALL_LABOR_BILL_RATES);
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
