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
        $this->rules['labor_head_id']                = ['required'];
        $this->rules['rate']                = ['nullable'];
       
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
