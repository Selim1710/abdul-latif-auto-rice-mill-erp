<?php

namespace Modules\LaborHead\Http\Requests;
use App\Http\Requests\FormRequest;


class LaborHeadFormRequest extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['mobile']         = ['required','string','max:15','unique:labor_heads,mobile'];
        if(request()->update_id){
            $this->rules['mobile'][3]  = 'unique:labor_heads,mobile,'.request()->update_id;
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
