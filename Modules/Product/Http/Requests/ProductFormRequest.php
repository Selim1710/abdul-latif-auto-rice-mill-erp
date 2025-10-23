<?php

namespace Modules\Product\Http\Requests;

use App\Http\Requests\FormRequest;

class ProductFormRequest extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['product_name']                 = ['required','string','unique:products,product_name'];
        $this->rules['category_id']                  = ['required'];
        $this->rules['product_code']                 = ['required','string','unique:products,product_code'];
        $this->rules['purchase_price']               = ['required','numeric','gte:0'];
        $this->rules['sale_price']                   = ['required','numeric','gte:0'];
        $this->rules['unit_id']                      = ['required'];
        $this->rules['alert_quantity']               = ['nullable','numeric','gte:0'];
        if(request()->update_id){
            $this->rules['product_name'][2] = 'unique:products,product_name,'.request()->update_id;
            $this->rules['product_code'][2] = 'unique:products,product_code,'.request()->update_id;
        }
        if(request()->opening_stock == 1) {
            if(request()->has('openingStock')){
                foreach (request()->openingStock as $key => $value){
                    $this->rules ['openingStock.'.$key.'.warehouse_id']        = ['required'];
                    $this->rules ['openingStock.'.$key.'.scale']               = ['required'];
                    $this->rules ['openingStock.'.$key.'.qty']                 = ['required'];
                }
            }
        }
        return $this->rules;
    }
    public function messages(){
        return $this->messages;
    }
    public function authorize(){
        return true;
    }
//    public function rules(){
//        $rules['product_name']                 = ['required','string','unique:products,product_name'];
//        $rules['category_id']                  = ['required'];
//        $rules['product_code']                 = ['required','string','unique:products,product_code'];
//        $rules['purchase_price']               = ['required','numeric','gte:0'];
//        $rules['sale_price']                   = ['required','numeric','gte:0'];
//        $rules['unit_id']                      = ['required'];
//        $rules['alert_quantity']               = ['nullable','numeric','gte:0'];
////        $rules['opening_stock_qty']            = ['nullable','numeric'];
////        $rules['opening_warehouse_id']         = ['nullable','numeric'];
//        if(request()->update_id){
//            $rules['product_name'][2] = 'unique:products,product_name,'.request()->update_id;
//            $rules['product_code'][2] = 'unique:products,product_code,'.request()->update_id;
//        }
//        if(request()->opening_stock == 1) {
//            $rules['opening_stock_qty'][0]    = 'required';
//            $rules['opening_warehouse_id'][0] = 'required';
//        }
//        return $rules;
//    }
//    public function messages(){
//        return [
//            'unit_id.required'      => 'The unit name field is required',
//        ];
//    }
//    public function authorize(){
//        return true;
//    }
}
