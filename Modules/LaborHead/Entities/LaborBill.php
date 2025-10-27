<?php

namespace Modules\LaborHead\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class LaborBill extends BaseModel
{
    protected $fillable = ['date', 'invoice_no', 'labor_head_id', 'grand_total', 'status', 'narration', 'created_by'];
    protected $table    = 'labor_bills';
    protected $order    = ['id' => 'DESC'];
    protected $_from_date;
    protected $_to_date;

    public function laborHead(): BelongsTo
    {
        return $this->belongsTo(LaborHead::class, 'labor_head_id', 'id');
    }
    public function laborBillRate(): BelongsTo
    {
        return $this->belongsTo(LaborBillRate::class, 'labor_bill_rate_id', 'id');
    }
    public function setFromDate($from_date)
    {
        $this->_from_date = $from_date;
    }
    public function setToDate($to_date)
    {
        $this->_to_date = $to_date;
    }
    private function get_datatable_query()
    {
        $this->column_order = ['date', 'invoice_no', 'labor_head_id', 'grand_total', 'status', 'created_by', null];
        $query              = self::toBase();
        if (!empty($this->_from_date)) {
            $query->where('date', '>=', $this->_from_date);
        }
        if (!empty($this->_to_date)) {
            $query->where('date', '<=', $this->_to_date);
        }
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
}
