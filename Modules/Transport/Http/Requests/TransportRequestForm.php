<?php

namespace Modules\Transport\Http\Requests;

use App\Http\Requests\FormRequest;

class TransportRequestForm extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['date']           = ['required'];
        $this->rules['driver_name']    = ['required'];
        $this->rules['driver_phone']   = ['required'];
        $this->rules['truck_id']       = ['required'];
        $this->rules['party_type']     = ['required'];
        $this->rules['rent_amount']    = ['required'];
        $this->rules['total_expense']  = ['required'];
        $this->rules['income']         = ['required'];
        if(request()->party_type == 1){
            $this->rules['party_id']       = ['required'];
        }
        if(request()->party_type == 2){
            $this->rules['party_name']     = ['required'];
        }
        if(request()->has('transport')){
            foreach (request()->transport as $key => $value) {
                $this->rules    ['transport.'.$key.'.expense_item_id']    = ['required'];
                $this->rules    ['transport.'.$key.'.amount']             = ['required'];
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
