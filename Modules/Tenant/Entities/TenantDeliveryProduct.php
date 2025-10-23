<?php

namespace Modules\Tenant\Entities;

use App\Models\BaseModel;

class TenantDeliveryProduct extends BaseModel {
    protected $fillable = ['invoice_no','tenant_id','date','name','number','status','note','created_by'];
    protected $table    = 'tenant_delivery_products';
    public function tenant(){
        return $this->belongsTo(Tenant::class,'tenant_id','id');
    }
    public function tenantDeliveryProduct(){
        return $this->belongsToMany(TenantDeliveryProductList::class,'tenant_delivery_product_lists','tenant_delivery_product_id','warehouse_id')->withTimestamps();
    }
    public function tenantDeliveryProductList(){
        return $this->hasMany(TenantDeliveryProductList::class,'tenant_delivery_product_id','id');
    }
    private function get_datatable_query(){
        $this->column_order = ['id','invoice_no','tenant_id','date','name','number','status','created_by', null];
        $query              = self::with('tenant');
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
