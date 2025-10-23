<?php

namespace Modules\Transport\Entities;

use App\Models\BaseModel;
use Modules\Party\Entities\Party;
use Modules\Transport\Entities\Truck;

class Transport extends BaseModel{
    protected $fillable = ['invoice_no','date','truck_id','party_type','party_id','party_name','driver_name','driver_phone','rent_name','rent_amount','total_expense','income','note','status','created_by','modified_by'];
    protected $_voucher_no;
    protected $_from_date;
    protected $_to_date;
    protected $_truck_id;
    public function party(){
        return $this->belongsTo(Party::class,'party_id','id');
    }
    public function truck(){
        return $this->belongsTo(Truck::class,'truck_id','id');
    }
    public function transportDetails(){
        return $this->belongsToMany(TransportDetail::class,'transport_details','transport_id','expense_item_id','id','id')->withTimestamps();
    }
    public function transportDetailsList(){
        return $this->hasMany(TransportDetail::class,'transport_id','id');
    }
    public function setVoucherNo($voucher_no){
        $this->_voucher_no = $voucher_no;
    }
    public function setTruckID($truck_id){
        $this->_truck_id = $truck_id;
    }
    private function get_datatable_query(){
        $this->column_order = ['invoice_no','date','truck_id','party_type','party_id','party_name','driver_name','driver_phone','rent','total_expense','income','note','status','created_by','modified_by', null];
        $query = self::with('party','truck');
        if (!empty($this->_voucher_no)) {
            $query->where('voucher_no', 'like', '%' . $this->_voucher_no . '%');
        }
        if (!empty($this->_truck_id)) {
            $query->where('truck_id', $this->_truck_id );
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
