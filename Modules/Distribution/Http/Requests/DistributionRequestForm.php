<?php

namespace Modules\Distribution\Http\Requests;

use App\Http\Requests\FormRequest;

class DistributionRequestForm extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['date']                = ['required'];
        $this->messages['date']             = 'This Field Is Required';
        $this->rules['receiver_name']       = ['required'];
        $this->messages['receiver_name']    = 'This Field Is Required';
        if(request()->update_id){
            $this->rules['invoice_no'][1]   = 'unique:distributions,invoice_no,'.request()->update_id;
        }
        if(request()->has('distribution')){
            foreach (request()->distribution as $key => $value){
                $this->rules ['distribution.'.$key.'.warehouse_id']    = ['required'];
                $this->messages['distribution'.$key.'.warehouse_id']   = 'This Field Is Required';
                $this->rules ['distribution.'.$key.'.product_id']      = ['required'];
                $this->messages['distribution.'.$key.'.product_id']    = 'This Field Is Required';
                $this->rules ['distribution.'.$key.'.qty']             = ['required'];
                $this->messages['distribution.'.$key.'.qty']           = 'This Field Is Required';
                $this->rules ['distribution.'.$key.'.dis_qty']         = ['required'];
                $this->messages['distribution.'.$key.'.dis_qty']       = 'This Field Is Required';
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
