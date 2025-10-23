<?php

namespace Modules\Loan\Entities;

use App\Models\BaseModel;

class LoanCategories extends BaseModel {
    protected $fillable = ['name','status','created_by'];
    protected $table    = 'loan_categories';
    private function get_datatable_query(){
        $this->column_order = ['id','name','status','created_by',null];
        $query = self::toBase();
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
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
