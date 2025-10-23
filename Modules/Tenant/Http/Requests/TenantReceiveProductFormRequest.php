<?php

namespace Modules\Tenant\Http\Requests;

use App\Http\Requests\FormRequest;

class TenantReceiveProductFormRequest extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['date']          = ['required'];
        $this->messages['date']       = 'This Field Is Required';
        $this->rules['tenant_id']     = ['required'];
        $this->messages['tenant_id']  = 'This Field Is Required';
        if(request()->has('tenant_receive')){
            foreach (request()->tenant_receive as $key => $value){
                $this->rules ['tenant_receive.'.$key.'.warehouse_id']     = ['required'];
                $this->messages['tenant_receive'.$key.'.warehouse_id']    = 'This Field Is Required';
                $this->rules ['tenant_receive.'.$key.'.product_id']       = ['required'];
                $this->messages['tenant_receive.'.$key.'.product_id']     = 'This Field Is Required';
                $this->rules ['tenant_receive.'.$key.'.qty']              = ['required'];
                $this->messages['tenant_receive.'.$key.'.qty']            = 'This Field Is Required';
                $this->rules ['tenant_receive.'.$key.'.scale']            = ['required'];
                $this->messages['tenant_receive.'.$key.'.scale']          = 'This Field Is Required';
                $this->rules ['tenant_receive.'.$key.'.rec_qty']          = ['required'];
                $this->messages['tenant_receive.'.$key.'.rec_qty']        = 'This Field Is Required';

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
