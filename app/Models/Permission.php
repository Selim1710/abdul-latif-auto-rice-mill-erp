<?php

namespace App\Models;

use App\Models\BaseModel;

class Permission extends BaseModel
{

    protected $fillable = ['module_id','name','slug']; //fillable column name


    /******************************************
     * * * Begin :: Custom Datatable Code * * *
    *******************************************/
    //custom search column property
    protected $moduleID;
    protected $name;

    //methods to set custom search property value
    public function setModuleID($moduleID)
    {
        $this->moduleID = $moduleID;
    }
    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)
        if (permission('permission-bulk-delete')){
            $this->column_order = [null,'id','module_id','name','slug',null];
        }else{
            $this->column_order = ['id','module_id','name','slug',null];
        }
        

        $query = self::with('module:id,module_name');

        //search query
        if (!empty($this->moduleID)) {
            $query->where('module_id', $this->moduleID);
        }
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }

        //order by data fetching code
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
    /******************************************
     * * * End :: Custom Datatable Code * * *
    *******************************************/


    
    /***************************************
     * * * Begin :: Model Relationship * * *
    ****************************************/
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function permission_role() {
        return $this->hasMany(PermissionRole::class);
    }
    /***************************************
     * * * End :: Model Relationship * * *
    ****************************************/


}
