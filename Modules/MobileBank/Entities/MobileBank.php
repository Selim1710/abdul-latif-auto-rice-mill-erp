<?php

namespace Modules\MobileBank\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;

class MobileBank extends BaseModel{
    protected $fillable                    = [ 'bank_name', 'account_name', 'account_number', 'status', 'created_by', 'modified_by'];
    protected const ALL_MOBILE_BANKS       = '_mobile_banks';
    protected const ACTIVE_MOBILE_BANKS    = '_active_mobile_banks';
    protected $_bank_name;
    protected $_account_name;
    protected $_account_number;
    public function setBankName($bank_name){
        $this->_bank_name = $bank_name;
    }
    public function setAccountName($account_name){
        $this->_account_name = $account_name;
    }
    public function setAccountNumber($account_number){
        $this->_account_number = $account_number;
    }
    private function get_datatable_query(){
        $this->column_order = ['id','bank_name','account_name','account_number','status',null];
        $query              = self::toBase()->select('id','bank_name','account_name','account_number','status');
        if (!empty($this->_bank_name)) {
            $query->where('bank_name', 'like', '%' . $this->_bank_name . '%');
        }
        if (!empty($this->_account_name)) {
            $query->where('account_name', 'like', '%' . $this->_account_name . '%');
        }
        if (!empty($this->_account_number)) {
            $query->where('account_number', 'like', '%' . $this->_account_number . '%');
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
    public static function allMobileBankList(){
        return Cache::rememberForever(self::ALL_MOBILE_BANKS, function () {
            return self::toBase()->orderBy('bank_name','asc')->get();
        });
    }
    public static function activeMobileBankList(){
        return Cache::rememberForever(self::ACTIVE_MOBILE_BANKS, function () {
            return self::toBase()->where('status',1)->orderBy('bank_name','asc')->get();
        });
    }
    public static function flushCache(){
        Cache::forget(self::ALL_MOBILE_BANKS);
        Cache::forget(self::ACTIVE_MOBILE_BANKS);
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
