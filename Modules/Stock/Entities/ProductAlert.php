<?php

namespace Modules\Stock\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;

class ProductAlert extends BaseModel{
    protected $order    = ['p.id' => 'asc'];
    protected $_product_id;
    public function setProductID($product_id){
        $this->_product_id = $product_id;
    }
    private function get_datatable_query(){
        $this->column_order = ['p.id','p.product_name','p.product_code','p.alert_qty','c.category_name','u.unit_name','u.unit_code','p.price','wp.qty',null];
        $query              = DB::table('products as p')
                              ->join('categories as c','p.category_id','=','c.id')
                              ->join('units as u','p.unit_id','=','u.id')
                              ->join('warehouse_product as wp','wp.product_id','=','p.id')
                              ->selectRaw('p.product_name as productName , p.product_code as productCode , p.alert_qty as alertQty , c.category_name as categoryName , u.unit_name as unitName , u.unit_code as unitCode , p.sale_price as price , sum(IFNULL(wp.qty,0)) as warehouseQty');
        if($this->_product_id != 0) {
            $query->where('p.id',  $this->_product_id);
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
        return DB::table('products as p')
            ->join('categories as c','p.category_id','=','c.id')
            ->join('units as u','p.unit_id','=','u.id')
            ->join('warehouse_product as wp','wp.product_id','=','p.id')
            ->selectRaw('p.product_name as productName , p.product_code as productCode , p.alert_qty as alertQty , c.category_name as categoryName , u.unit_name as unitName , u.unit_code as unitCode , p.sale_price as price , sum(IFNULL(wp.qty,0)) as warehouseQty')
            ->get()->count();
    }
}
