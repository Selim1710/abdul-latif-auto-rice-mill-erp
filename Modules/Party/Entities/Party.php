<?php

namespace Modules\Party\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Modules\ChartOfHead\Entities\ChartOfHead;

class Party extends BaseModel {
    protected $fillable = [ 'name', 'company_name', 'mobile', 'address','previous_balance','balance_type' ,'status', 'created_by', 'modified_by'];
    protected $table    = 'parties';
    protected $_party_id;
    protected $_mobile;
    public function chartOfHead(){
        return $this->hasOne(ChartOfHead::class,'party_id','id');
    }
    public function balance(int $partyId){
        return DB::table('chart_of_heads as coh')
                ->join('transactions as t','coh.id','=','t.chart_of_head_id')
                ->where(['coh.party_id' => $partyId,'t.status' => 1])
                ->select(
                    DB::raw('SUM(t.debit) as totalDebit'),
                    DB::raw('SUM(t.credit) as totalCredit'),
                )
                ->first();
    }
    public function setMobile($mobile){
        $this->_mobile = $mobile;
    }
    public function setPartyId($party_id){
        $this->_party_id = $party_id;
    }
    private function get_datatable_query(){
        $this->column_order = [ 'name', 'company_name', 'mobile', 'address','previous_balance','balance_type' ,'status', 'created_by', 'modified_by', null];
        $query              = self::toBase();
        if (!empty($this->_party_id)) {
            $query->where('id', $this->_party_id);
        }
        if (!empty($this->_mobile)) {
            $query->where('mobile', 'like', '%' . $this->_mobile . '%');
        }
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
