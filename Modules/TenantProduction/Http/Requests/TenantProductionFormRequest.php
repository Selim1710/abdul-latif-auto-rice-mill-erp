<?php

namespace Modules\TenantProduction\Http\Requests;

use App\Http\Requests\FormRequest;

class TenantProductionFormRequest extends FormRequest
{
    protected $rules;
    protected $messages;
    public function rules()
    {
        $this->rules['tenant_id']    = ['required'];
        $this->messages['tenant_id'] = 'This Field Is Required';
        $this->rules['mill_id']      = ['required'];
        $this->messages['mill_id']   = 'This Field Is Required';
        $this->rules['date']         = ['required'];
        $this->messages['date']      = 'This Field Is Required';
        if (request()->has('production')) {
            foreach (request()->production as $key => $value) {
                $this->rules['production.' . $key . '.warehouse_id']    = ['required'];
                $this->messages['production' . $key . '.warehouse_id']   = 'This Field Is Required';
                $this->rules['production.' . $key . '.product_id']      = ['required'];
                $this->messages['production.' . $key . '.product_id']    = 'This Field Is Required';
                $this->rules['production.' . $key . '.qty']             = ['required'];
                $this->messages['production.' . $key . '.qty']           = 'This Field Is Required';
                $this->rules['production.' . $key . '.scale']           = ['required'];
                $this->messages['production.' . $key . '.scale']         = 'This Field Is Required';
                $this->rules['production.' . $key . '.pro_qty']         = ['required'];
                $this->messages['production.' . $key . '.pro_qty']       = 'This Field Is Required';
            }
        }

        $this->rules['total_product_qty'] = [
            function ($attribute, $value, $fail) {
                $totalQty = collect(request()->input('production', []))
                    ->sum('pro_qty');

                if ($totalQty < 22000 || $totalQty > 24000) {
                    $fail("The total product quantity must be between 22,000 and 24,000. Current total: {$totalQty}");
                }
            }
        ];

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
