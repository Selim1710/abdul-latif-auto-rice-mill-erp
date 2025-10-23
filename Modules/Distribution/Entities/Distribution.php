<?php

namespace Modules\Distribution\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class Distribution extends BaseModel {
    protected $fillable               = ['invoice_no','date','receiver_name','distribution_status','created_by'];
    protected $table                  = 'distributions';
    protected const ALL_DISTRIBUTIONS = '_distributions';
    protected $_receiver_name;
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function distributionProduct(){
        return $this->belongsToMany(DistributionProduct::class,'distribution_products','distribution_id','warehouse_id')->withTimestamps();
    }
    public function distributionProductList(){
        return $this->hasMany(DistributionProduct::class,'distribution_id','id');
    }
    public function setReceiverName($receiver_name){
        $this->_receiver_name = $receiver_name;
    }
    private function get_datatable_query(){
        $this->column_order = ['invoice_no','date','receiver_name','distribution_status','created_by',null];
        $query              = self::with('distributionProductList');
        if (!empty($this->_receiver_name)) {
            $query->where('receiver_name', 'like', '%' . $this->_receiver_name . '%');
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
    public static function allDistributionList(){
        return Cache::rememberForever(self::ALL_DISTRIBUTIONS,function(){
            return self::toBase()->latest()->get();
        });
    }
    public static function flushCache(){
        Cache::forget(self::ALL_DISTRIBUTIONS);
    }
    public static function boot(){
        parent::boot();
        static::updated(function(){
            self::flushCache();
        });
        static::created(function(){
            self::flushCache();
        });
        static::deleted(function(){
            self::flushCache();
        });
    }
}
