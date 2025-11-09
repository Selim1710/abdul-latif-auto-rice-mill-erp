<?php

namespace Modules\TenantProduction\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Mill\Entities\Mill;
use Modules\Tenant\Entities\Tenant;

class TenantProduction extends BaseModel
{
    protected $fillable                       = ['invoice_no', 'batch_no', 'production_batch_no', 'production_type', 'tenant_id', 'mill_id', 'date', 'start_date', 'end_date', 'total_raw_scale', 'total_merge_scale', 'total_use_product_qty', 'total_milling', 'total_expense', 'total_delivery_scale', 'total_stock_scale', 'production_status', 'note', 'created_by', 'modified_by'];
    protected $table                          = 'tenant_productions';
    protected const ALL_TENANT_PRODUCTIONS    = '_tenant_productions';
    protected $_invoice_no;
    protected $_mill_id;
    protected $_start_date;
    protected $_end_date;
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }
    public function mill()
    {
        return $this->belongsTo(Mill::class, 'mill_id', 'id');
    }
    public function raw(): BelongsToMany
    {
        return $this->belongsToMany(TenantProductionRawProduct::class, 'tenant_production_raw_products', 'tenant_production_id', 'warehouse_id')->withTimestamps();
    }
    public function rawList(): HasMany
    {
        return $this->hasMany(TenantProductionRawProduct::class, 'tenant_production_id', 'id');
    }
    public function expense(): BelongsToMany
    {
        return $this->belongsToMany(TenantProductionExpense::class, 'tenant_production_expenses', 'tenant_production_id', 'expense_item_id')->withTimestamps();
    }
    public function expenseList(): HasMany
    {
        return $this->hasMany(TenantProductionExpense::class, 'tenant_production_id', 'id');
    }
    public function product(): BelongsToMany
    {
        return $this->belongsToMany(TenantProductionProduct::class, 'tenant_production_products', 'tenant_production_id', 'warehouse_id')->withTimestamps();
    }
    public function productList(): HasMany
    {
        return $this->hasMany(TenantProductionProduct::class, 'tenant_production_id', 'id');
    }
    public function deliveryList(): HasMany
    {
        return $this->hasMany(TenantProductionDelivery::class, 'tenant_production_id', 'id');
    }
    public function mergeProduct(): BelongsToMany
    {
        return $this->belongsToMany(TenantProductionMergeProduct::class, 'tenant_production_merge_products', 'tenant_production_id', 'warehouse_id')->withTimestamps();
    }
    public function mergeProductList(): HasMany
    {
        return $this->hasMany(TenantProductionMergeProduct::class, 'tenant_production_id', 'id');
    }
    public function setInvoiceNo($invoice_no)
    {
        $this->_invoice_no = $invoice_no;
    }
    public function setMillId($mill_id)
    {
        $this->_mill_id = $mill_id;
    }
    public function setStartDate($start_date)
    {
        $this->_start_date = $start_date;
    }
    public function setEndDate($end_date)
    {
        $this->_end_date = $end_date;
    }
    private function get_datatable_query()
    {
        $this->column_order = ['invoice_no', 'tenant_id', 'mill_id', 'date', 'start_date', 'end_date', 'total_raw_scale', 'total_merge_scale', 'total_use_product_qty', 'total_milling', 'total_expense', 'total_delivery_scale', 'total_stock_scale', 'production_status', 'note', 'created_by', 'modified_by', null];
        $query              = self::with(
            [
                'mill',
                'deliveryList' => function ($q) {
                    $q->groupBy('invoice_no');
                },
                'productList'  => function ($q1) {
                    $q1->groupBy('invoice_no');
                }
            ]
        );
        if (!empty($this->_invoice_no)) {
            $query->where('invoice_no', 'like', '%' . $this->_invoice_no . '%');
        }
        if (!empty($this->_mill_id)) {
            $query->where('mill_id', $this->_mill_id);
        }
        if (!empty($this->_start_date)) {
            $query->where('start_date', '>=', $this->_start_date);
        }
        if (!empty($this->_end_date)) {
            $query->where('end_date', '<=', $this->_end_date);
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
    public static function allTenantProductionList()
    {
        return Cache::rememberForever(self::ALL_TENANT_PRODUCTIONS, function () {
            return self::toBase()->latest()->get();
        });
    }
    public static function flushCache()
    {
        Cache::forget(self::ALL_TENANT_PRODUCTIONS);
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
