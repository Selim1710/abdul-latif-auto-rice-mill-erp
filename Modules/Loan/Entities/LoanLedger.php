<?php

namespace Modules\Loan\Entities;

use App\Models\BaseModel;

class LoanLedger extends BaseModel {
    protected $fillable = ['id','chart_of_account_id','voucher_no','voucher_type','voucher_date','description','loan_id','debit','credit'];
    protected $table    = 'transactions';
    private const TYPE  = 'LOAN';
    public function loan(){
        return $this->belongsTo(Loan::class,'loan_id','id');
    }
    private function get_datatable_query(){
        $this->column_order = ['id','chart_of_account_id','voucher_no','voucher_type','voucher_date','description','loan_id','debit','credit',null];
        $query = self::with('loan','loan.bank')->where(['voucher_type' => self::TYPE,'approve' => 1]);
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
        return self::with('loan','loan.bank')->where(['voucher_type' => self::TYPE,'approve' => 1])->get()->count();
    }
}
