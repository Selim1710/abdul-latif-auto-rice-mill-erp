<?php

namespace Modules\Account\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\ChartOfHead;

class Voucher extends BaseModel{
    protected $fillable = ['chart_of_head_id','date', 'voucher_no', 'voucher_type', 'narration', 'debit', 'credit','status', 'is_opening', 'created_by', 'modified_by'];
    protected $table    = 'transactions';
    protected $order    = ['t.id' => 'DESC'];
    protected $_from_date;
    protected $_to_date;
    public function coh(){
        return $this->belongsTo(ChartOfHead::class,'chart_of_head_id','id');
    }
    public function setFromDate($from_date){
        $this->_from_date = $from_date;
    }
    public function setToDate($to_date){
        $this->_to_date = $to_date;
    }
    private function get_datatable_query(){
        $this->column_order = ['t.id','coh.name as chartOfHeadName','t.date', 't.voucher_no', 't.voucher_type', 't.narration', 't.debit', 't.credit','t.status', 't.created_by',null];
        $query              = DB::table('transactions as t')
                              ->join('chart_of_heads as coh','t.chart_of_head_id','=','coh.id')
                              ->whereIn('t.voucher_type', ['DEBIT-VOUCHER','CREDIT-VOUCHER','JOURNAL-VOUCHER','CONTRA-VOUCHER'])
                              ->select('coh.name as chartOfHeadName', 't.date as date', 't.voucher_no as voucherNo', 't.voucher_type as voucherType', 't.narration as narration', 't.status as status', 't.created_by as createdBy',
                                DB::raw('SUM(t.debit) as totalDebit'),
                                DB::raw('SUM(t.credit) as totalCredit'),
                              )
                              ->groupBy('t.voucher_no');
        if (!empty($this->_from_date)) {
            $query->where('t.date', '>=',$this->_from_date);
        }
        if (!empty($this->_to_date)) {
            $query->where('t.date', '<=',$this->_to_date);
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
