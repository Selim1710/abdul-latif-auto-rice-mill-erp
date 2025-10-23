<?php

namespace Modules\ChartOfHead\Entities;

use App\Models\BaseModel;

class SubHead extends BaseModel {
    protected $fillable = ['master_head','type','head_id','sub_head_id','name','classification'];
    protected $table    = 'chart_of_heads';
    protected $_name;
    public function head(){
        return $this->belongsTo(Head::class,'head_id','id');
    }
    public function childHead(){
        return $this->hasMany(SubHead::class,'sub_head_id','id')->where(['type' => 3]);
    }
    public function setName($name){
        $this->_name = $name;
    }
    private function get_datatable_query(){
        $this->column_order = ['master_head','type','head_id','sub_head_id','name','classification',null];
        $query              = self::with('head:id,name')->where(['type' => 2]);
        if (!empty($this->_name)) {
            $query->where('name', 'like', '%' . $this->_name . '%');
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
