<?php

namespace Modules\Production\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Mill\Entities\Mill;

class Production extends BaseModel {
    protected $fillable                = ['invoice_no','batch_no','production_type','mill_id','date','start_date','end_date','total_raw_scale','total_raw_amount','total_use_product_qty','total_use_product_amount','total_milling','total_expense','total_sale_scale','total_sale_amount','total_stock_scale','total_stock_amount','per_unit_scale_cost','production_status','note','created_by','modified_by'];
    protected $table                   = 'productions';
    protected const ALL_PRODUCTIONS    = '_productions';
    protected $_invoice_no;
    protected $_mill_id;
    protected $_start_date;
    protected $_end_date;
    public function mill(){
        return $this->belongsTo(Mill::class,'mill_id','id');
    }
    public function productionRawProduct(){
        return $this->belongsToMany(ProductionRawProduct::class,'production_raw_products','production_id','warehouse_id')->withTimestamps();
    }
    public function productionRawProductList(){
        return $this->hasMany(ProductionRawProduct::class,'production_id','id');
    }
    public function productionSaleList(){
        return $this->hasMany(ProductionSale::class,'production_id','id');
    }
    public function productionProduct(){
        return $this->belongsToMany(ProductionProduct::class,'production_products','production_id','warehouse_id')->withTimestamps();
    }
    public function productionProductList(){
        return $this->hasMany(ProductionProduct::class,'production_id','id');
    }
    public function productionProductInvoice(){
        return $this->hasMany(ProductionProduct::class,'production_id','id')->groupBy('invoice_no');
    }
    public function productionExpense(){
        return $this->belongsToMany(ProductionExpense::class,'production_expenses','production_id','expense_item_id');
    }
    public function productionExpenseList(){
        return $this->hasMany(ProductionExpense::class,'production_id','id');
    }
    public function setInvoiceNo($invoice_no){
        $this->_invoice_no = $invoice_no;
    }
    public function setMillId($mill_id){
        $this->_mill_id = $mill_id;
    }
    public function setStartDate($start_date){
        $this->_start_date = $start_date;
    }
    public function setEndDate($end_date){
        $this->_end_date = $end_date;
    }
    private function get_datatable_query(){
        $this->column_order = ['invoice_no','mill_id','date','start_date','end_date','total_raw_amount','total_milling','total_expense','production_status','created_by',null];
        $query              = self::with('mill','productionSaleList','productionProductInvoice');
        if (!empty($this->_invoice_no)) {
            $query->where('invoice_no', 'like', '%' . $this->_invoice_no . '%');
        }
        if (!empty($this->_mill_id)) {
            $query->where('mill_id', $this->_mill_id);
        }
        if (!empty($this->_start_date)) {
            $query->where('start_date', '>=',$this->_start_date);
        }
        if (!empty($this->_end_date)) {
            $query->where('end_date', '<=',$this->_end_date);
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
    public static function allProductionList(){
        return Cache::rememberForever(self::ALL_PRODUCTIONS, function () {
            return self::toBase()->latest()->get();
        });
    }
    public static function flushCache(){
        Cache::forget(self::ALL_PRODUCTIONS);
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
