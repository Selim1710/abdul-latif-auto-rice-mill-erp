<?php

namespace Modules\LaborHead\Http\Requests;

use App\Http\Requests\FormRequest;

class LaborBillRateFormRequest extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules()
    {
        $this->rules['warehouse_id']        = ['nullable'];
        $this->rules['name']                = ['required', 'unique:labor_bill_rates,name'];
        $this->rules['rate']                = ['required'];
        if (request()->update_id) {
            $this->rules['name'][1]         = 'unique:labor_bill_rates,name,' . request()->update_id;
        }
        return $this->rules;
    }
    public function messages()
    {
        return $this->messages;
    }
    public function authorize()
    {
        return true;
    }
}
