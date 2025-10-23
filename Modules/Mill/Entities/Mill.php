<?php

namespace Modules\Mill\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Modules\Account\Entities\ChartOfAccount;

class Mill extends BaseModel {
    protected $fillable = ['name','address','asset_price','status','created_by'];
    protected $table    = 'mills';
    protected $_name;
    protected $_address;
    public function chartOfAccount(){
        return $this->hasOne(ChartOfAccount::class,'mill_id','id');
    }
    public function setName($name){
        $this->_name = $name;
    }
    public function setAddress($address){
        $this->_address = $address;
    }
    private function getDataTableQuery(){
        $this->column_order = ['id','name','address','status','created_by',null];
        $query = self::toBase();
        if (!empty($this->_name)) {
            $query->where('name', 'like', '%' . $this->_name . '%');
        }
        if (!empty($this->_address)) {
            $query->where('address', 'like', '%' . $this->_address . '%');
        }
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }
    public function getDatatableList(){
        $query = $this->getDataTableQuery();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }
    public function count_filtered(){
        $query = $this->getDataTableQuery();
        return $query->get()->count();
    }
    public function count_all(){
        return self::toBase()->get()->count();
    }
    protected const ALL_MILLS    = '_mills';

    public static function allMillList(){
        return Cache::rememberForever(self::ALL_MILLS, function () {
            return self::toBase()->orderBy('name','asc')->get();
        });
    }
    public static function flushCache(){
        Cache::forget(self::ALL_MILLS);
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
