<?php

namespace Modules\Account\Http\Requests;

use App\Http\Requests\FormRequest;

class VoucherFormRequest extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['voucher_no']                = ['required'];
        $this->rules['date']                      = ['required'];
        $this->rules['voucher_type']              = ['required'];
        if(request()->has('debit')){
            foreach (request()->debit as $key => $value) {
                $this->rules    ['debit.'.$key.'.chart_of_head_id']  = ['required'];
                $this->rules    ['debit.'.$key.'.debit']             = ['required'];
            }
        }
        if(request()->has('credit')){
            foreach (request()->credit as $key => $value) {
                $this->rules    ['credit.'.$key.'.chart_of_head_id']  = ['required'];
                $this->rules    ['credit.'.$key.'.credit']            = ['required'];
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
