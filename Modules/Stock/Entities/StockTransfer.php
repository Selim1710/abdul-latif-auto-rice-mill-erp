<?php

namespace Modules\Stock\Entities;

use App\Models\BaseModel;
use Modules\Setting\Entities\Warehouse;

class StockTransfer extends BaseModel
{
    protected $fillable = ['transfer_date', 'invoice_no', 'transfer_warehouse_id', 'receive_warehouse_id', 'status', 'created_by'];
    protected $table = 'stock_transfers';
    public function transferWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'transfer_warehouse_id', 'id');
    }
    public function receiveWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'receive_warehouse_id', 'id');
    }
    public function stockTransferWarehouseProduct()
    {
        return $this->belongsToMany(StockTransferWarehouseProduct::class, 'stock_transfer_warehouse_products', 'stock_transfer_id', 'product_id');
    }
    public function stockTransferWarehouseProductList()
    {
        return $this->hasMany(StockTransferWarehouseProduct::class, 'stock_transfer_id', 'id');
    }
    private function get_datatable_query()
    {
        $this->column_order = ['transfer_date', 'invoice_no', 'transfer_warehouse_id', 'receive_warehouse_id', 'status', 'created_by', null];
        $query = self::with('transferWarehouse', 'receiveWarehouse');
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
