<?php

namespace Modules\LaborHead\Http\Requests;
use App\Http\Requests\FormRequest;


class LaborHeadFormRequest extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['name']         = ['required','string','max:15','unique:labor_heads,name'];
        if(request()->update_id){
            $this->rules['name'][3]  = 'unique:labor_heads,name,'.request()->update_id;
        }
        return $this->rules;
    }
    public function messages(){
        return $this->messages;
    }
    public function authorize(){
        return true;
    }
}
