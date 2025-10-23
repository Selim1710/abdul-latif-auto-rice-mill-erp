<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Entities\Transaction;
use Modules\ChartOfHead\Entities\ChartOfHead;

class IncomeStatementReport extends Model
{
    protected $table    = 'transactions';
    public function chartOfAccount(){
        return $this->hasOne(ChartOfHead::class,'chart_of_head_id','id');
    }
    public function collectTransactionID(){
        $bank = ChartOfHead::where('classification','5')->get()->toArray();
        $mobileBank = ChartOfHead::where('classification',6)->get()->toArray();
        $cash = ChartOfHead::where('name','Cash In Hand')->where('id','24')->get()->toArray();
        $collection = [];
        foreach (array_merge($bank,$mobileBank,$cash) as $value){
            $collection[] = collect($value['id']);
        }
        return $collection;
    }
    public function sale($date){
        $sales      = Transaction::where('voucher_type','SALE')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalSales = Transaction::where('voucher_type','SALE')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('debit');
        return [$sales,$totalSales];
    }

    public function collection($date)
    {
        $collection      = Transaction::where('voucher_type','COLLECTION')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $partyCollection = Transaction::where('voucher_type','COLLECTION')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('debit');
        return [$collection,$partyCollection];
    }

    public function tenant($date){
        $tenant     = Transaction::where('voucher_type','TENANT-COLLECTION')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalTenant= Transaction::where('voucher_type','TENANT-COLLECTION')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('debit');
        return [$tenant,$totalTenant];
    }
    public function purchase($date){
        $purchase        = Transaction::where('voucher_type','Purchase')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalPurchase   = Transaction::where('voucher_type','Purchase')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('credit');
        return [$purchase,$totalPurchase];
    }

    public function supplierPayment($date){
        $payment        = Transaction::where('voucher_type','PAYMENT')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalPayment   = Transaction::where('voucher_type','PAYMENT')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('credit');
        return [$payment,$totalPayment];
    }


    public function personalLoan($date){
        $incomePersonalLoan       = Transaction::where('voucher_type','PL')->where('created_at','LIKE','%'.$date.'%')->where('credit',0)->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalIncomePersonalLoan  = Transaction::where('voucher_type','PL')->where('created_at','LIKE','%'.$date.'%')->where('credit',0)->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('debit');
        $expensePersonalLoan      = Transaction::where('voucher_type','PL')->where('created_at','LIKE','%'.$date.'%')->where('debit',0)->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalExpensePersonalLoan = Transaction::where('voucher_type','PL')->where('created_at','LIKE','%'.$date.'%')->where('debit',0)->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('credit');
        return [$incomePersonalLoan,$totalIncomePersonalLoan,$expensePersonalLoan,$totalExpensePersonalLoan];
    }
    public function officialLoan($date){
        $incomeOfficialLoan       = Transaction::where('voucher_type','LOAN')->where('created_at','LIKE','%'.$date.'%')->where('credit',0)->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalIncomeOfficialLoan  = Transaction::where('voucher_type','LOAN')->where('created_at','LIKE','%'.$date.'%')->where('credit',0)->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('debit');
        $expenseOfficialLoan      = Transaction::where('voucher_type','LOAN')->where('created_at','LIKE','%'.$date.'%')->where('debit',0)->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalExpenseOfficialLoan = Transaction::where('voucher_type','LOAN')->where('created_at','LIKE','%'.$date.'%')->where('debit',0)->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('credit');
        return [$incomeOfficialLoan,$totalIncomeOfficialLoan,$expenseOfficialLoan,$totalExpenseOfficialLoan];
    }
    public function machinePurchase($date){
        $machinePurchase          = Transaction::where('voucher_type','Machine Purchase')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalMachinePurchase     = Transaction::where('voucher_type','Machine Purchase')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('credit');
        return [$machinePurchase,$totalMachinePurchase];
    }
    public function machineService($date){
        $machineService           = Transaction::where('voucher_type','Maintenance Service')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalMachineService      = Transaction::where('voucher_type','Maintenance Service')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('debit');
        return [$machineService,$totalMachineService];
    }
    public function transportService($date){
        $transportService         = Transaction::where('voucher_type','Transport Service')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalTransportService    = Transaction::where('voucher_type','Transport Service')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('debit');
        return [$transportService,$totalTransportService];
    }
    public function laborBill($date){
        $laborBill       = Transaction::where('voucher_type','LABOR-BILL')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalLaborBill  = Transaction::where('voucher_type','LABOR-BILL')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('credit');
        return [$laborBill,$totalLaborBill];
    }
    public function expense($date){
        $expense         = Transaction::where('voucher_type','Expense')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->get();
        $totalExpense    = Transaction::where('voucher_type','Expense')->where('created_at','LIKE','%'.$date.'%')->whereIn('chart_of_head_id',$this->collectTransactionID())->sum('credit');
        return [$expense,$totalExpense];
    }
    public function cash($date){
        $cash = ChartOfHead::where('name','Cash In Hand')->where('id','24')->first();
        $data = Transaction::where('created_at','LIKE','%'.$date.'%')->where('chart_of_head_id',$cash['id'])->get();
        $debit = 0 ; $credit = 0;
        foreach ($data as $value){
            if($value->debit == 0){
                $credit = $credit + $value->credit;
            }else{
                $debit  = $debit + $value->debit;
            }
        }
        $netBalance = $debit - $credit;
        return $netBalance;
    }
    public function bank(){
        return ChartOfHead::where('classification','5')->get();
    }
    public function mobileBank(){
        return ChartOfHead::where('classification','6')->get();
    }
}
