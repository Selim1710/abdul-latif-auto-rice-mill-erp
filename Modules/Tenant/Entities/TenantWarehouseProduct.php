<?php

namespace Modules\Tenant\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Warehouse;

class TenantWarehouseProduct extends BaseModel
{
    protected $fillable = ['tenant_id', 'warehouse_id', 'product_id', 'qty', 'scale', 'tenant_product_type', 'batch_no'];
    protected $table    = 'tenant_warehouse_products';
    protected $order    = ['twp.id' => 'asc'];
    protected $_tenant_id;
    protected $_warehouse_id;
    protected $_product_id;
    protected $_category_id;
    protected $_batch_number;

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function setTenantID($tenant_id)
    {
        $this->_tenant_id = $tenant_id;
    }
    public function setWarehouseID($warehouse_id)
    {
        $this->_warehouse_id = $warehouse_id;
    }
    public function setProductID($product_id)
    {
        $this->_product_id = $product_id;
    }
    public function setCategoryID($category_id)
    {
        $this->_category_id = $category_id;
    }

    public function set_batch_number($batch_number)
    {
        $this->_batch_number = $batch_number;
    }

    private function get_datatable_query()
    {
        $this->column_order = ['twp.id', 't.name', 'w.name', 'p.product_name', 'p.product_code', 'c.category_name', 'u.unit_code', 'u.unit_name', 'twp.qty', 'twp.scale', null];
        $query  =  DB::table('tenant_warehouse_products as twp')
            ->join('tenants as t', 'twp.tenant_id', '=', 't.id')
            ->join('warehouses as w', 'twp.warehouse_id', '=', 'w.id')
            ->join('products as p', 'twp.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->join('units as u', 'p.unit_id', '=', 'u.id')
            ->where([['twp.qty', '!=', 0]])
            ->select('t.name as tenantName', 'twp.batch_no as batch_no',  'twp.tenant_product_type as tenant_product_type', 'w.name as warehouseName', 'p.product_name as productName', 'p.product_code as productCode', 'c.category_name as categoryName', 'u.unit_code as unitCode', 'u.unit_name as unitName', 'twp.qty as qty', 'twp.scale as scale');
        if ($this->_tenant_id != 0) {
            $query->where('twp.tenant_id', $this->_tenant_id);
        }
        if ($this->_warehouse_id != 0) {
            $query->where('twp.warehouse_id', $this->_warehouse_id);
        }
        if ($this->_product_id != 0) {
            $query->where('twp.product_id',  $this->_product_id);
        }
        if ($this->_category_id != 0) {
            $query->where('p.category_id', $this->_category_id);
        }
        if ($this->_batch_number != 0) {
            $query->where('twp.batch_no', $this->_batch_number);
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
