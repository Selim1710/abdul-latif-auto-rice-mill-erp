<?php

namespace Modules\ChartOfHead\Http\Requests;

use App\Http\Requests\FormRequest;

class HeadRequestForm extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['master_head']             = ['required'];
        $this->messages['master_head']          = 'This Field Is Required';
        $this->rules['name']                    = ['required'];
        $this->messages['name']                 = 'This Field Is Required';
        return $this->rules;
    }
    public function authorize(){
        return true;
    }
    public function messages(){
        return $this->messages;
    }
}
