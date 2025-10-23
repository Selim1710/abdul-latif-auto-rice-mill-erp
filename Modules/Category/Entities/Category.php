<?php

namespace Modules\Category\Entities;

use App\Models\BaseModel;

class Category extends BaseModel {
    protected $fillable = ['category_name','status','created_by','modified_by'];
    protected $table    = 'categories';
    protected $_category_name;
    protected $_status;
    public function scopeMaterialCategory($query){
        return $query->where(['type' => 1])->get();
    }
    public function scopeProductCategory($query){
        return $query->where(['type' => 2])->get();
    }
    public function setCategoryName($category_name){
        $this->_category_name = $category_name;
    }
    public function setStatus($status){
        $this->_status   = $status;
    }
    private function get_datatable_query(){
        $this->column_order = ['category_name','status','created_by','modified_by',null];
        $query = self::toBase();
        if (!empty($this->_category_name)) {
            $query->where('category_name', 'like', '%' . $this->_category_name . '%');
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
