<?php

namespace Modules\Production\Http\Requests;

use App\Http\Requests\FormRequest;

class ProductionCompleteFormRequest extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        if(request()->has('raws')){
            foreach (request()->raws as $key => $value){
                $this->rules ['raws.'.$key.'.use_qty']        = ['required'];
                $this->messages['raws.'.$key.'.use_qty']      = 'This Field Is Required';
                $this->rules ['raws.'.$key.'.use_scale']      = ['required'];
                $this->messages['raws.'.$key.'.use_scale']    = 'This Field Is Required';
                $this->rules ['raws.'.$key.'.use_pro_qty']    = ['required'];
                $this->messages['raws.'.$key.'.use_pro_qty']  = 'This Field Is Required';
                $this->rules ['raws.'.$key.'.rate']           = ['required'];
                $this->messages['raws.'.$key.'.rate']         = 'This Field Is Required';
                $this->rules ['raws.'.$key.'.milling']        = ['required'];
                $this->messages['raws.'.$key.'.milling']      = 'This Field Is Required';
            }
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
