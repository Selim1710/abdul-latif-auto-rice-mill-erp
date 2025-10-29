<?php

namespace Modules\Product\Entities;


use App\Models\Unit;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Category\Entities\Category;
use Modules\Setting\Entities\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Stock\Entities\OpeningStock;
use Modules\Stock\Entities\WarehouseProduct;

class Product extends BaseModel
{
    use HasFactory;
    protected $fillable = ['product_name', 'product_code', 'category_id', 'unit_id', 'sale_unit_id', 'purchase_price', 'sale_price', 'alert_qty', 'opening_stock', 'opening_warehouse_id', 'opening_stock_qty', 'status', 'created_by', 'modified_by'];
    protected $table    = 'products';
    protected $_product_name;
    protected $_product_code;
    protected $_category_id;
    protected $_status;
    public function setProductName($product_name)
    {
        $this->_product_name = $product_name;
    }
    public function setProductCode($product_code)
    {
        $this->_product_code = $product_code;
    }
    public function setCategoryId($category_id)
    {
        $this->_category_id = $category_id;
    }
    public function setStatus($status)
    {
        $this->_status = $status;
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function opening_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'opening_warehouse_id', 'id')->withDefault(['name' => '']);
    }
    public function openingStock(): BelongsToMany
    {
        return $this->belongsToMany(OpeningStock::class, 'opening_stocks', 'product_id', 'warehouse_id')->withTimestamps();
    }
    public function openingStockList(): HasMany
    {
        return $this->hasMany(OpeningStock::class, 'product_id', 'id');
    }
    public function warehouseProduct(): HasMany
    {
        return $this->hasMany(WarehouseProduct::class, 'product_id', 'id');
    }
    private function get_datatable_query()
    {
        $this->column_order = ['product_name', 'product_code', 'category_id', 'unit_id', 'sale_unit_id', 'price', 'alert_qty', 'opening_stock', 'opening_warehouse_id', 'opening_stock_qty', 'status', 'created_by', 'modified_by', null];
        $query              = self::with('category:id,category_name', 'unit:id,unit_name,unit_code');
        if (!empty($this->_product_name)) {
            $query->where('product_name', 'like', '%' . $this->_product_name . '%');
        }
        if (!empty($this->_product_code)) {
            $query->where('product_code', 'like', '%' . $this->_product_code . '%');
        }
        if (!empty($this->_category_id)) {
            $query->where('category_id', $this->_category_id);
        }
        if (!empty($this->_status)) {
            $query->where('status', $this->_status);
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
}
