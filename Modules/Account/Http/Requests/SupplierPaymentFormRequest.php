<?php

namespace Modules\Account\Http\Requests;

use App\Http\Requests\FormRequest;

class SupplierPaymentFormRequest extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['voucher_no']       = ['required'];
        $this->rules['date']             = ['required'];
        $this->rules['party_id']         = ['required'];
        $this->rules['payment_method']   = ['required'];
        $this->rules['account_id']       = ['required'];
        $this->rules['amount']           = ['required'];
        return $this->rules;
    }
    public function messages(){
        return $this->messages;
    }
    public function authorize(){
        return true;
    }
}
