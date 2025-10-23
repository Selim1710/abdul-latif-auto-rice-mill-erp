<?php

namespace Modules\Mill\Http\Requests;

use App\Http\Requests\FormRequest;

class MillRequestForm extends FormRequest{
    public function rules(){
        $rules['name']               = ['required','unique:mills,name'];
        $rules['asset_price']        = ['required'];
        if(request()->update_id){
            $rules['name'][1]        = 'unique:mills,name,'.request()->update_id;
        }
        return $rules;
    }
    public function authorize(){
        return true;
    }
}
