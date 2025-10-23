<?php

namespace Modules\Party\Http\Requests;

use App\Http\Requests\FormRequest;

class PartyRequestForm extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['name']                  = ['required','string','max:100'];
        $this->messages['name']               = 'This Field Is Required';
        $this->rules['company_name']          = ['nullable','string','max:100'];
        $this->messages['company_name']       = 'Need String Value That Less Than 100 Character';
        $this->rules['mobile']                = ['required','string','max:15','unique:parties,mobile'];
        $this->messages['mobile']             = 'This Field Is Required';
        $this->rules['address']               = ['nullable','string'];
        $this->messages['address']            = 'Something Wrong';
        $this->rules['previous_balance']      = ['nullable','numeric'];
        $this->messages['previous_balance']   = 'Something Wrong';
        if(!empty(request()->previous_balance)){
            $this->rules['balance_type']                  = ['required'];
            $this->messages['balance_type']               = 'This Field Is Required';
        }
        if(request()->id){
            $this->rules['mobile'][3]           = 'unique:parties,mobile,'.request()->id;
        }
        return $this->rules;
    }
    public function authorize(){
        return true;
    }
    public function messages(){
        return $this->messages;
    }
}
