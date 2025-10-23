<?php

namespace Modules\Purchase\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;

class Purchase extends BaseModel {
    protected $fillable           = ['invoice_no','purchase_date','transport_name','party_type','party_id','party_name','purchase_status','document','discount','total_purchase_qty','total_receive_qty','total_return_qty','total_purchase_sub_total','total_receive_sub_total','total_return_sub_total','previous_due','net_total','paid_amount','due_amount','payment_status','payment_method','account_id','created_by','modified_by'];
    protected $table              = 'purchases';
    protected const ALL_PURCHASES = '_purchases';
    protected $_invoice_no;
    protected $_party_id;
    protected $_from_date;
    protected $_to_date;
    public function party(){
        return $this->belongsTo(Party::class,'party_id','id');
    }
    public function purchaseProduct(){
        return $this->belongsToMany(PurchaseProduct::class,'purchase_products','purchase_id', 'warehouse_id')->withTimeStamps();
    }
    public function purchaseProductList(){
        return $this->hasMany(PurchaseProduct::class,'purchase_id','id');
    }
    public function purchaseProductReceive(){
        return $this->belongsToMany(PurchaseProductReceive::class,'purchase_product_receives','purchase_id','warehouse_id')->withTimestamps();
    }
    public function purchaseProductReceiveList(){
        return $this->hasMany(PurchaseProductReceive::class,'purchase_id','id');
    }
    public function purchaseProductReceiveInvoiceList(){
        return $this->hasMany(PurchaseProductReceive::class,'purchase_id','id')->groupBy('invoice_no');
    }
    public function purchaseProductReturn(){
        return $this->belongsToMany(PurchaseProductReturn::class,'purchase_product_returns','purchase_id','warehouse_id')->withTimestamps();
    }
    public function purchaseProductReturnList(){
        return $this->hasMany(PurchaseProductReturn::class,'purchase_id','id');
    }
    public function purchaseProductReturnInvoiceList(){
        return $this->hasMany(PurchaseProductReturn::class,'purchase_id','id')->groupBy('invoice_no');
    }
    public function setInvoiceNo($invoice_no){
        $this->_invoice_no = $invoice_no;
    }
    public function setPartyId($party_id){
        $this->_party_id = $party_id;
    }
    public function setFromDate($from_date){
        $this->_from_date = $from_date;
    }
    public function setToDate($to_date){
        $this->_to_date = $to_date;
    }
    private function get_datatable_query(){
        $this->column_order = ['id','invoice_no','purchase_date','party_type','party_id','party_name','purchase_status','document','shipping_cost','labor_cost','total_purchase_qty','total_receive_qty','total_return_qty','total_purchase_sub_total','total_receive_sub_total','total_return_sub_total','previous_due','net_total','paid_amount','due_amount','payment_status','payment_method','account_id','created_by','modified_by', null];
        $query              = self::with('party','purchaseProductReceiveInvoiceList','purchaseProductReturnInvoiceList');
        if (!empty($this->_invoice_no)) {
            $query->where('invoice_no', 'like', '%' . $this->_invoice_no . '%');
        }
        if (!empty($this->_party_id)) {
            $query->where('party_id', $this->_party_id);
        }
        if (!empty($this->_from_date)) {
            $query->where('purchase_date', '>=',$this->_from_date);
        }
        if (!empty($this->_to_date)) {
            $query->where('purchase_date', '<=',$this->_to_date);
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
    public static function allPurchaseList(){
        return Cache::rememberForever(self::ALL_PURCHASES,function(){
           return self::toBase()->latest()->get();
        });
    }
    public static function flushCache(){
        Cache::forget(self::ALL_PURCHASES);
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
