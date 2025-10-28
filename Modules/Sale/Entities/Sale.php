<?php

namespace Modules\Sale\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;
use Modules\Customer\Entities\Customer;
use Modules\Setting\Entities\Warehouse;

class Sale extends BaseModel
{
    protected $fillable          = ['invoice_no', 'sale_date', 'transport_name', 'party_type', 'party_id', 'party_name', 'sale_type', 'sale_status', 'document', 'discount', 'total_sale_qty', 'total_delivery_qty', 'total_return_qty', 'total_sale_sub_total', 'total_delivery_sub_total', 'total_return_sub_total', 'previous_due', 'net_total', 'paid_amount', 'due_amount', 'payment_status', 'payment_method', 'account_id', 'created_by', 'modified_by','total_load_unload'];
    protected $table             = 'sales';
    protected const ALL_SALES    = '_sales';
    protected $_invoice_no;
    protected $_party_id;
    protected $_from_date;
    protected $_to_date;
    protected $_sale_status;
    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'id');
    }
    public function saleProduct()
    {
        return $this->belongsToMany(SaleProduct::class, 'sale_products', 'sale_id', 'warehouse_id')->withTimestamps();
    }
    public function saleProductList()
    {
        return $this->hasMany(SaleProduct::class, 'sale_id', 'id');
    }
    public function saleProductDelivery()
    {
        return $this->belongsToMany(SaleProductDelivery::class, 'sale_product_deliveries', 'sale_id', 'warehouse_id')->withTimestamps();
    }
    public function saleProductDeliveryList()
    {
        return $this->hasMany(SaleProductDelivery::class, 'sale_id', 'id');
    }
    public function saleProductDeliveryInvoiceList()
    {
        return $this->hasMany(SaleProductDelivery::class, 'sale_id', 'id')->groupBy('invoice_no');
    }
    public function saleProductReturn()
    {
        return $this->belongsToMany(SaleProductReturn::class, 'sale_product_returns', 'sale_id', 'warehouse_id')->withTimestamps();
    }
    public function saleProductReturnList()
    {
        return $this->hasMany(SaleProductReturn::class, 'sale_id', 'id');
    }
    public function saleProductReturnInvoiceList()
    {
        return $this->hasMany(SaleProductReturn::class, 'sale_id', 'id')->groupBy('invoice_no');
    }
    public function setInvoiceNo($invoice_no)
    {
        $this->_invoice_no = $invoice_no;
    }
    public function setPartyId($party_id)
    {
        $this->_party_id = $party_id;
    }
    public function setFromDate($from_date)
    {
        $this->_from_date = $from_date;
    }
    public function setToDate($to_date)
    {
        $this->_to_date = $to_date;
    }
    public function setSaleStatus($sale_status)
    {
        $this->_sale_status = $sale_status;
    }
    private function get_datatable_query()
    {
        $this->column_order = ['id', 'invoice_no', 'sale_date', 'party_type', 'party_id', 'party_name', 'sale_type', 'sale_status', 'document', 'discount', 'total_sale_qty', 'total_delivery_qty', 'total_return_qty', 'total_sale_sub_total', 'total_delivery_sub_total', 'total_return_sub_total', 'previous_due', 'net_total', 'paid_amount', 'due_amount', 'payment_status', 'payment_method', 'account_id', 'created_by', 'modified_by', null];
        $query = self::with('party', 'saleProductDeliveryInvoiceList', 'saleProductReturnInvoiceList');
        if (!empty($this->_invoice_no)) {
            $query->where('invoice_no', 'like', '%' . $this->_invoice_no . '%');
        }
        if (!empty($this->_party_id)) {
            $query->where('party_id', $this->_party_id);
        }
        if (!empty($this->_from_date)) {
            $query->where('sale_date', '>=', $this->_from_date);
        }
        if (!empty($this->_to_date)) {
            $query->where('sale_date', '<=', $this->_to_date);
        }
        if (!empty($this->_sale_status)) {
            $query->where('sale_status', $this->_sale_status);
        }
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
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
    public static function allSaleList()
    {
        return Cache::rememberForever(self::ALL_SALES, function () {
            return self::toBase()->latest()->get();
        });
    }
    public static function flushCache()
    {
        Cache::forget(self::ALL_SALES);
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
