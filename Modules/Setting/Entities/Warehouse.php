<?php

namespace Modules\Setting\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Modules\LaborHead\Entities\LaborBillDetail;
use Modules\LaborHead\Entities\LaborBillRateDetail;
use Modules\Material\Entities\Material;

class Warehouse extends BaseModel
{
    protected $fillable = ['name', 'phone', 'email', 'address', 'status', 'deletable', 'created_by', 'modified_by'];
    protected $name;
    public function materials()
    {
        return $this->hasMany(Material::class, 'warehouse_materials', 'warehouse_id', 'material_id', 'id', 'id')->withTimeStamps()->withPivot('qty');
    }

    public function labour_load_unload_head()
    {
        return $this->hasOne(LaborBillRateDetail::class, 'warehouse_id', 'id')->where('labor_head_id', 1);
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    private function get_datatable_query()
    {
        $this->column_order = ['id', 'name', 'phone', 'email', 'address', null, null, 'status', null];
        $query              = self::toBase();
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
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
