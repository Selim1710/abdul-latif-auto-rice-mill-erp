<?php

namespace Modules\Stock\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;
use Modules\Purchase\Entities\Purchase;
use Modules\Setting\Entities\Warehouse;

class WarehouseProduct extends BaseModel
{
    protected $fillable = ['warehouse_id', 'product_id', 'scale', 'qty', 'per_scale_transportation_cost', 'purchase_id', 'party_id', 'purchase_price'];
    protected $table    = 'warehouse_product';
    protected $order    = ['wp.id' => 'asc'];
    protected $_category_id;
    protected $_product_id;
    protected $_warehouse_id;
    protected $_party_id;

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function setCategoryID($category_id)
    {
        $this->_category_id = $category_id;
    }
    public function setProductID($product_id)
    {
        $this->_product_id = $product_id;
    }
    public function setWarehouseID($warehouse_id)
    {
        $this->_warehouse_id = $warehouse_id;
    }
    public function set_party_id($party_id)
    {
        $this->_party_id = $party_id;
    }
    private function get_datatable_query()
    {
        $this->column_order = ['wp.id', 'w.name', 'p.product_name', 'p.product_code', 'c.category_name', 'u.unit_code', 'u.unit_name', 'wp.scale as scale', 'wp.qty', null];
        $query  =  DB::table('warehouse_product as wp')
            ->leftJoin('warehouses as w', 'wp.warehouse_id', '=', 'w.id')
            ->leftJoin('products as p', 'wp.product_id', '=', 'p.id')
            ->leftJoin('categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('units as u', 'p.unit_id', '=', 'u.id')
            ->leftJoin('purchases as pur', 'wp.purchase_id', '=', 'pur.id')
            ->leftJoin('parties as par', 'wp.party_id', '=', 'par.id')
            ->where([['wp.qty', '!=', 0]])
            ->select('w.name as warehouseName','wp.purchase_price as purchase_price', 'p.product_name as productName', 'p.product_code as productCode', 'c.category_name as categoryName', 'u.unit_code as unitCode', 'u.unit_name as unitName', 'p.sale_price as productPrice', 'wp.scale as scale', 'wp.qty as qty', 'wp.scale as scale', 'pur.invoice_no as invoice_no', 'par.name as party_name');
        if ($this->_category_id != 0) {
            $query->where('p.category_id', $this->_category_id);
        }
        if ($this->_product_id != 0) {
            $query->where('wp.product_id',  $this->_product_id);
        }
        if ($this->_warehouse_id != 0) {
            $query->where('wp.warehouse_id', $this->_warehouse_id);
        }
        if ($this->_party_id != 0) {
            $query->where('wp.party_id', $this->_party_id);
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
}
