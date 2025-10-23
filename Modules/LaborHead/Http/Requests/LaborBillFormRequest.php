<?php

namespace Modules\LaborHead\Http\Requests;

use App\Http\Requests\FormRequest;

class LaborBillFormRequest extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['labor_head_id']       = ['required'];
        return $this->rules;
    }
    public function messages(){
        return $this->messages;
    }
    public function authorize(){
        return true;
    }
}
