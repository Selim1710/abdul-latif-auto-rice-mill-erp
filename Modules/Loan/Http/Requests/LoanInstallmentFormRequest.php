<?php

namespace Modules\Loan\Http\Requests;

use App\Http\Requests\FormRequest;

class LoanInstallmentFormRequest extends FormRequest{
    protected $rules = [];
    protected $messages = [];
    public function rules(){
        $this->rules['voucher_no']                = ['required'];
        $this->rules['voucher_date']              = ['required'];
        $this->rules['amount']                    = ['required'];
        $this->rules['loan_id']                   = ['required'];
        if(request()->has('loan')){
            foreach (request()->loan as $key => $value) {
                $this->rules    ['loan.'.$key.'.debitId']                   = ['required'];
                $this->rules    ['loan.'.$key.'.debitAmount']               = ['required'];
                $this->messages ['loan.'.$key.'.debitId.required']          = 'The account field is required';
                $this->messages ['loan.'.$key.'.debitAmount.required']      = 'The account field is required';
            }
        }
        return $this->rules;
    }
    public function messages(){
        return $this->messages;
    }
    public function authorize(){
        return true;
    }
}
