<?php

namespace Modules\Loan\Entities;

use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Modules\Bank\Entities\Bank;

class Loan extends BaseModel {
    protected $fillable     = ['loan_categories_id','bank_id','reference_no','loan_amount','loan_percentage_percent','loan_percentage_tk','processing_charge','misc_charge','apply_date','total_amount','total_installment_month','monthly_installment','document','note','start_date','status','created_by'];
    protected $table        = 'loans';
    protected $order        = ['l.id' => 'desc'];
    protected $_loan_categories;
    protected $_bank;
    protected $_total_amount;
    protected $_monthly_installment;
    public function loanCategories(){
        return $this->belongsTo(LoanCategories::class,'loan_categories_id','id');
    }
    public function bank(){
        return $this->belongsTo(Bank::class,'bank_id','id');
    }
    public function setLoanCategories($loan_categories){
        $this->_loan_categories = $loan_categories;
    }
    public function setBank($bank){
        $this->_bank = $bank;
    }
    public function totalAmount($total_amount){
        $this->_total_amount = $total_amount;
    }
    public function setMonthlyInstallment($monthly_installment){
        $this->_monthly_installment = $monthly_installment;
    }
    private function getDataTableQuery(){
        $this->column_order = ['l.id,lc.name','b.bank_name','b.branch','b.account_number','l.total_amount','l.monthly_installment','l.status','l.created_by',null];
        $query = DB::table('loans as l')
                 ->join('loan_categories as lc','l.loan_categories_id','=','lc.id')
                 ->join('banks as b','l.bank_id','=','b.id')
                 ->select('l.id as id','lc.name as categoriesName','b.bank_name as bankName','b.branch as bankBranch','b.account_number as bankAccountNumber','l.total_amount as totalAmount','l.monthly_installment as monthlyInstallment','l.status as status','l.created_by as createdBy');
        if(!empty($this->_loan_categories)){
            $query->where('l.loan_categories_id','=',$this->_loan_categories);
        }
        if(!empty($this->_bank)){
            $query->where('l.bank_id','=',$this->_bank);
        }
        if(!empty($this->_total_amount)){
            $query->where('l.total_amount', 'like', '%' . $this->_total_amount . '%');
        }
        if(!empty($this->_monthly_installment)){
            $query->where('l.monthly_installment', 'like', '%' . $this->_monthly_installment . '%');
        }
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }
    public function getDatatableList(){
        $query = $this->getDataTableQuery();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }
    public function count_filtered(){
        $query = $this->getDataTableQuery();
        return $query->get()->count();
    }
    public function count_all(){
        return DB::table('loans as l')
               ->join('loan_categories as lc','l.loan_categories_id','=','lc.id')
               ->join('banks as b','l.bank_id','=','b.id')
               ->select('l.id as id','lc.name as categoriesName','b.bank_name as bankName','b.branch as bankBranch','b.account_number as bankAccountNumber','l.total_amount as totalAmount','l.monthly_installment as monthlyInstallment','l.status as status','l.created_by as createdBy')
               ->get()->count();
    }
}
