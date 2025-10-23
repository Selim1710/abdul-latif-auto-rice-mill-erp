<?php

namespace Modules\Production\Http\Requests;

use App\Http\Requests\FormRequest;

class ProductionProductFormRequest extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['date']               = ['required'];
        $this->messages['date']            = 'This Field Is Required';
        if(request()->update_id){
            $this->rules['invoice_no'][1]      = 'unique:production_products,invoice_no,'.request()->update_id;
        }
        if(request()->has('production_product')){
            foreach (request()->production_product as $key => $value){
                $this->rules ['production_product.'.$key.'.warehouse_id']           = ['required'];
                $this->messages['production_product.'.$key.'.warehouse_id']         = 'This Field Is Required';
                $this->rules ['production_product.'.$key.'.product_id']             = ['required'];
                $this->messages['production_product.'.$key.'.product_id']           = 'This Field Is Required';
                $this->rules ['production_product.'.$key.'.qty']                    = ['required'];
                $this->messages['production_product.'.$key.'.qty']                  = 'This Field Is Required';
                $this->rules ['production_product.'.$key.'.scale']                  = ['required'];
                $this->messages['production_product.'.$key.'.scale']                = 'This Field Is Required';
                $this->rules ['production_product.'.$key.'.production_qty']         = ['required'];
                $this->messages['production_product.'.$key.'.production_qty']       = 'This Field Is Required';
                $this->rules ['production_product.'.$key.'.price']                  = ['required'];
                $this->messages['production_product.'.$key.'.price']                = 'This Field Is Required';
                if(!empty(request()->production_product[$key]['use_warehouse_id'])){
                    $this->rules ['production_product.'.$key.'.use_warehouse_id']   = ['required'];
                    $this->messages['production_product.'.$key.'.use_warehouse_id'] = 'This Field Is Required';
                    $this->rules ['production_product.'.$key.'.use_product_id']     = ['required'];
                    $this->messages['production_product.'.$key.'.use_product_id']   = 'This Field Is Required';
                    $this->rules ['production_product.'.$key.'.use_qty']            = ['required'];
                    $this->messages['production_product.'.$key.'.use_qty']          = 'This Field Is Required';
                    $this->rules ['production_product.'.$key.'.use_price']          = ['required'];
                    $this->messages['production_product.'.$key.'.use_price']        = 'This Field Is Required';
                }
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
