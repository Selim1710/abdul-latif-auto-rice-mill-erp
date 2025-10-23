<?php

namespace Modules\Tenant\Entities;

use App\Models\BaseModel;

class TenantReceiveProduct extends BaseModel {
    protected $fillable = ['invoice_no','tenant_id','date','status','note','created_by'];
    protected $table    = 'tenant_receive_products';
    protected $_tenant_id;
    public function tenant(){
        return $this->belongsTo(Tenant::class,'tenant_id','id');
    }
    public function tenantReceiveProduct(){
        return $this->belongsToMany(TenantReceiveProductList::class,'tenant_receive_product_lists','tenant_receive_product_id','warehouse_id')->withTimestamps();
    }
    public function tenantReceiveProductList(){
        return $this->hasMany(TenantReceiveProductList::class,'tenant_receive_product_id','id');
    }
    public function setTenantID($tenant_id){
        $this->_tenant_id = $tenant_id;
    }
    private function get_datatable_query(){
        $this->column_order = ['id','invoice_no','tenant_id','date','status','created_by', null];
        $query              = self::with('tenant','tenantReceiveProductList');
        if($this->_tenant_id != 0){
            $query->where('tenant_id', $this->_tenant_id);
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
}
