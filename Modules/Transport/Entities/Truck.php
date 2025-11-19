<?php

namespace Modules\Transport\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;

class Truck extends BaseModel
{
    protected $fillable = ['truck_no', 'asset_price', 'status', 'created_by', 'modified_by'];
    protected $_truck_no;
    protected $_status;
    public function setTruckNo($truck_no)
    {
        $this->_truck_no = $truck_no;
    }
    public function setStatus($status)
    {
        $this->_status = $status;
    }
    private function get_datatable_query()
    {
        $this->column_order = ['id', 'truck_no', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at', null];
        $query = self::toBase();
        if (!empty($this->_truck_no)) {
            $query->where('truck_no', 'like', '%' . $this->_truck_no . '%');
        }
        if (!empty($this->_status)) {
            $query->where('status', $this->_status);
        }
        if (isset($this->orderValue) && isset($this->dirValue)) { //orderValue is the index number of table header and dirValue is asc or desc
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue); //fetch data order by matching column
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }
    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }
    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }
    public function count_all()
    {
        return self::toBase()->get()->count();
    }
    protected const TRUCKS     = '_trucks';
    public static function allTrucks()
    {
        return Cache::rememberForever(self::TRUCKS, function () {
            return self::where('status', 1)->orderBy('truck_no', 'asc')->get();
        });
    }
    public static function flushCache()
    {
        Cache::forget(self::TRUCKS);
    }
    public static function boot()
    {
        parent::boot();
        static::updated(function () {
            self::flushCache();
        });
        static::created(function () {
            self::flushCache();
        });
        static::deleted(function () {
            self::flushCache();
        });
    }
}
