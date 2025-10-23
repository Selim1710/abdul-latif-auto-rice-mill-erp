<?php

namespace Modules\Account\Http\Requests;

use App\Http\Requests\FormRequest;

class OpeningBalanceFormRequest extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['voucher_no']          = ['required'];
        $this->rules['date']                = ['required'];
        $this->rules['chart_of_head_id']    = ['required'];
        $this->rules['amount']              = ['required'];
        $this->rules['balance_type']        = ['required'];
        return $this->rules;
    }
    public function messages(){
        return $this->messages;
    }
    public function authorize(){
        return true;
    }
}
