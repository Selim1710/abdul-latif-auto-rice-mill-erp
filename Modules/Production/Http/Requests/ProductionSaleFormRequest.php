<?php

namespace Modules\Production\Http\Requests;

use App\Http\Requests\FormRequest;

class ProductionSaleFormRequest extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['sale_date']               = ['required'];
        $this->messages['sale_date']            = 'This Field Is Required';
        $this->rules['party_type']              = ['required'];
        $this->messages['party_type']           = 'This Field Is Required';
        $this->rules['payment_status']          = ['required'];
        $this->messages['payment_status']       = 'This Field Is Required';
        $this->rules['total_sale_sub_total']    = ['required'];
        $this->messages['total_sale_sub_total'] = 'This Field Is Required';
        $this->rules['total_sale_qty']          = ['required'];
        $this->messages['total_sale_qty']       = 'This Field Is Required';
        $this->rules['total_sale_scale']        = ['required'];
        $this->messages['total_sale_scale']     = 'This Field Is Required';
        if(request()->party_type == 1){
            $this->rules['party_id']       = ['required'];
            $this->messages['party_id']    = 'This Field Is Required';
        }
        if(request()->party_type == 2){
            $this->rules['party_name']     = ['required'];
            $this->messages['party_name']  = 'This Field Is Required';
        }
        if(request()->payment_status == 1 || request()->payment_status == 2){
            $this->rules['payment_method']     = ['required'];
            $this->messages['payment_method']  = 'This Field Is Required';
            $this->rules['account_id']         = ['required'];
            $this->messages['account_id']      = 'This Field Is Required';
        }
        if(request()->update_id){
            $this->rules['invoice_no'][1]      = 'unique:production_sales,invoice_no,'.request()->update_id;
        }
        if(request()->has('production_sale')){
            foreach (request()->production_sale as $key => $value){
                $this->rules ['production_sale.'.$key.'.product_id']     = ['required'];
                $this->messages['production_sale.'.$key.'.product_id']   = 'This Field Is Required';
                $this->rules ['production_sale.'.$key.'.qty']            = ['required'];
                $this->messages['production_sale.'.$key.'.qty']          = 'This Field Is Required';
                $this->rules ['production_sale.'.$key.'.scale']          = ['required'];
                $this->messages['production_sale.'.$key.'.scale']        = 'This Field Is Required';
                $this->rules ['production_sale.'.$key.'.sel_qty']        = ['required'];
                $this->messages['production_sale.'.$key.'.sel_qty']      = 'This Field Is Required';
                $this->rules ['production_sale.'.$key.'.price']          = ['required'];
                $this->messages['production_sale.'.$key.'.price']        = 'This Field Is Required';
                if(!empty(request()->production_sale[$key]['use_warehouse_id'])){
                    $this->rules ['production_sale.'.$key.'.use_product_id']     = ['required'];
                    $this->messages['production_sale.'.$key.'.use_product_id']   = 'This Field Is Required';
                    $this->rules ['production_sale.'.$key.'.use_qty']            = ['required'];
                    $this->messages['production_sale.'.$key.'.use_qty']          = 'This Field Is Required';
                    $this->rules ['production_sale.'.$key.'.use_price']          = ['required'];
                    $this->messages['production_sale.'.$key.'.use_price']        = 'This Field Is Required';
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
