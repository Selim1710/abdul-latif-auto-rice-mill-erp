<?php

namespace Modules\Production\Http\Requests;

use App\Http\Requests\FormRequest;

class ProductionFormRequest extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['batch_no']             = ['required'];
        $this->messages['batch_no']          = 'This Field Is Required';
        $this->rules['production_type']      = ['required'];
        $this->messages['production_type']   = 'This Field Is Required';
        $this->rules['mill_id']              = ['required'];
        $this->messages['mill_id']           = 'This Field Is Required';
        $this->rules['date']                 = ['required'];
        $this->messages['date']              = 'This Field Is Required';
        if(request()->has('production')){
            foreach (request()->production as $key => $value){
                $this->rules ['production.'.$key.'.warehouse_id']    = ['required'];
                $this->messages['production'.$key.'.warehouse_id']   = 'This Field Is Required';
                $this->rules ['production.'.$key.'.product_id']      = ['required'];
                $this->messages['production.'.$key.'.product_id']    = 'This Field Is Required';
                $this->rules ['production.'.$key.'.qty']             = ['required'];
                $this->messages['production.'.$key.'.qty']           = 'This Field Is Required';
                $this->rules ['production.'.$key.'.scale']           = ['required'];
                $this->messages['production.'.$key.'.scale']         = 'This Field Is Required';
                $this->rules ['production.'.$key.'.pro_qty']         = ['required'];
                $this->messages['production.'.$key.'.pro_qty']       = 'This Field Is Required';
            }
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
