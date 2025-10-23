<?php

namespace Modules\Loan\Http\Requests;

use App\Http\Requests\FormRequest;

class LoanFormRequest extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['loan_categories_id']          = ['required'];
        $this->messages['loan_categories_id']       = 'This Field Is Required';
        $this->rules['bank_id']                     = ['required'];
        $this->messages['bank_id']                  = 'This Field Is Required';
        $this->rules['reference_no']                = ['required'];
        $this->messages['reference_no']             = 'This Field Is Required';
        $this->rules['loan_amount']                 = ['required'];
        $this->messages['loan_amount']              = 'This Field Is Required';
        $this->rules['loan_percentage_percent']     = ['required'];
        $this->messages['loan_percentage_percent']  = 'This Field Is Required';
        $this->rules['loan_percentage_tk']          = ['required'];
        $this->messages['loan_percentage_tk']       = 'This Field Is Required';
        $this->rules['total_amount']                = ['required'];
        $this->messages['total_amount']             = 'This Field Is Required';
        $this->rules['apply_date']                  = ['required'];
        $this->messages['apply_date']               = 'This Field Is Required';
        $this->rules['total_installment_month']     = ['required'];
        $this->messages['total_installment_month']  = 'This Field Is Required';
        $this->rules['monthly_installment']         = ['required'];
        $this->messages['monthly_installment']      = 'This Field Is Required';
        return $this->rules;
    }
    public function authorize(){
        return true;
    }
    public function messages(){
        return $this->messages;
    }
}
