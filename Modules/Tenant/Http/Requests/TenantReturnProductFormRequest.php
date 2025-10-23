<?php

namespace Modules\Tenant\Http\Requests;

use App\Http\Requests\FormRequest;

class TenantReturnProductFormRequest extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['date']          = ['required'];
        $this->messages['date']       = 'This Field Is Required';
        $this->rules['tenant_id']     = ['required'];
        $this->messages['tenant_id']  = 'This Field Is Required';
        if(request()->has('tenant_return')){
            foreach (request()->tenant_return as $key => $value){
                $this->rules ['tenant_return.'.$key.'.warehouse_id']     = ['required'];
                $this->messages['tenant_return'.$key.'.warehouse_id']    = 'This Field Is Required';
                $this->rules ['tenant_return.'.$key.'.product_id']       = ['required'];
                $this->messages['tenant_return.'.$key.'.product_id']     = 'This Field Is Required';
                $this->rules ['tenant_return.'.$key.'.qty']              = ['required'];
                $this->messages['tenant_return.'.$key.'.qty']            = 'This Field Is Required';
                $this->rules ['tenant_return.'.$key.'.scale']            = ['required'];
                $this->messages['tenant_return.'.$key.'.scale']          = 'This Field Is Required';
                $this->rules ['tenant_return.'.$key.'.ret_qty']          = ['required'];
                $this->messages['tenant_return.'.$key.'.ret_qty']        = 'This Field Is Required';

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
