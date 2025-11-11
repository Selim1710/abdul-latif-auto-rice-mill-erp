<?php

namespace Modules\Tenant\Http\Requests;

use App\Http\Requests\FormRequest;

class TenantDeliveryProductFormRequest extends FormRequest
{
    protected $rules;
    protected $messages;

    public function rules()
    {
        $this->rules['date'] = ['required'];
        $this->messages['date'] = 'This Field Is Required';
        $this->rules['tenant_id'] = ['required'];
        $this->messages['tenant_id'] = 'This Field Is Required';
        $this->rules['total_load_unload_amount'] = ['required', 'numeric'];
        $this->messages['total_load_unload_amount'] = 'This Field Is Required';
        if (request()->has('tenant_delivery')) {
            foreach (request()->tenant_delivery as $key => $value) {
                $this->rules ['tenant_delivery.' . $key . '.warehouse_id'] = ['required'];
                $this->messages['tenant_delivery' . $key . '.warehouse_id'] = 'This Field Is Required';
                $this->rules ['tenant_delivery.' . $key . '.product_id'] = ['required'];
                $this->messages['tenant_delivery.' . $key . '.product_id'] = 'This Field Is Required';
                $this->rules ['tenant_delivery.' . $key . '.qty'] = ['required'];
                $this->messages['tenant_delivery.' . $key . '.qty'] = 'This Field Is Required';
                $this->rules ['tenant_delivery.' . $key . '.scale'] = ['required'];
                $this->messages['tenant_delivery.' . $key . '.scale'] = 'This Field Is Required';
                $this->rules ['tenant_delivery.' . $key . '.del_qty'] = ['required'];
                $this->messages['tenant_delivery.' . $key . '.del_qty'] = 'This Field Is Required';
                $this->rules ['tenant_delivery.' . $key . '.load_unload_rate'] = ['required'];
                $this->messages['tenant_delivery.' . $key . '.load_unload_rate'] = 'This Field Is Required';
                $this->rules ['tenant_delivery.' . $key . '.load_unload_amount'] = ['required'];
                $this->messages['tenant_delivery.' . $key . '.load_unload_amount'] = 'This Field Is Required';

            }
        }
        return $this->rules;
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return $this->messages;
    }
}
