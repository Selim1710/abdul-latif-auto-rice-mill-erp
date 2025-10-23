<?php

namespace Modules\Tenant\Entities;

use App\Models\BaseModel;

class Tenant extends BaseModel {
    protected $fillable   = ['name','mobile','address','status','created_by','modified_by'];
    protected $table      = 'tenants';
    protected $_name;
    protected $_mobile;
    protected $_status;
    public function scopeActive($query){
        return $query->where(['status'=>1]);
    }
    public function scopeInactive($query){
        return $query->where(['status'=>2]);
    }
    public function setName($name){
        $this->_name = $name;
    }
    public function setMobile($mobile){
        $this->_mobile = $mobile;
    }
    public function setStatus($status){
        $this->_status = $status;
    }
    private function get_datatable_query(){
        $this->column_order = ['id','name', 'mobile','status', 'created_by', 'modified_by', null];
        $query              = self::toBase();
        if (!empty($this->_name)) {
            $query->where('name', 'like', '%' . $this->_name . '%');
        }
        if (!empty($this->_mobile)) {
            $query->where('mobile', 'like', '%' . $this->_mobile . '%');
        }
        if (!empty($this->_status)) {
            $query->where('status', $this->_status);
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
