<?php

namespace Modules\Category\Http\Requests;

use App\Http\Requests\FormRequest;

class CategoryRequestForm extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['category_name']             = ['required','unique:categories,category_name'];
        $this->messages['category_name']          = 'This Field Is Required';
        if(request()->update_id){
            $this->rules['category_name'][1]      = 'unique:categories,category_name,'.request()->update_id;
            $this->messages['category_name']      = 'Duplicate Entry';
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
