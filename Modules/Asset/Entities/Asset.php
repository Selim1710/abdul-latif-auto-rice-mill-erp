<?php

namespace Modules\Asset\Entities;

use App\Models\BaseModel;

class Asset extends BaseModel
{
    protected $fillable = ['tag', 'name', 'asset_type_id', 'purchase_date', 'warranty', 
    'user', 'location', 'photo', 'cost', 'asset_status', 'description', 'status', 'created_by', 'modified_by'];

    public function asset_type()
    {
        return $this->belongsTo(AssetType::class);
    }

    /******************************************
     * * * Begin :: Custom Datatable Code * * *
    *******************************************/
    //custom search column property
    protected $_tag; 
    protected $_name; 
    protected $_asset_type_id; 
    protected $_asset_status; 

    //methods to set custom search property value
    public function setTag($tag)
    {
        $this->_tag = $tag;
    }
    public function setName($name)
    {
        $this->_name = $name;
    }
    public function setAssetTypeID($asset_type_id)
    {
        $this->_asset_type_id = $asset_type_id;
    }

    public function setAssetStatus($asset_status)
    {
        $this->_asset_status = $asset_status;
    }


    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)

        if (permission('asset-bulk-delete')){
            $this->column_order = [null,'id','photo','tag', 'name', 'asset_type_id', 'purchase_date','cost', 'warranty', 
            'user', 'location', 'asset_status','status',null];
        }else{
            $this->column_order = ['id','photo','tag', 'name', 'asset_type_id', 'purchase_date','cost', 'warranty', 
            'user', 'location', 'asset_status','status',null];
        }

        
        $query = self::with('asset_type');

        //search query
        if (!empty($this->_tag)) {
            $query->where('tag', 'like', '%' . $this->_tag . '%');
        }
        if (!empty($this->_name)) {
            $query->where('name', 'like', '%' . $this->_name . '%');
        }
        if (!empty($this->_asset_type_id)) {
            $query->where('asset_type_id', $this->_asset_type_id );
        }
        if (!empty($this->_asset_status)) {
            $query->where('asset_status', $this->_asset_status);
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

}
