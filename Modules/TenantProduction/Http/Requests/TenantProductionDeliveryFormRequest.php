<?php

namespace Modules\TenantProduction\Http\Requests;

use App\Http\Requests\FormRequest;

class TenantProductionDeliveryFormRequest extends FormRequest{
    protected $rules;
    protected $messages;
    public function rules(){
        $this->rules['date']                        = ['required'];
        $this->messages['date']                     = 'This Field Is Required';
        $this->rules['total_delivery_qty']          = ['required'];
        $this->messages['total_delivery_qty']       = 'This Field Is Required';
        $this->rules['total_delivery_scale']        = ['required'];
        $this->messages['total_delivery_scale']     = 'This Field Is Required';
        if(request()->update_id){
            $this->rules['invoice_no'][1]           = 'unique:tenant_production_deliveries,invoice_no,'.request()->update_id;
        }
        if(request()->has('production_delivery')){
            foreach (request()->production_delivery as $key => $value){
                $this->rules ['production_delivery.'.$key.'.product_id']     = ['required'];
                $this->messages['production_delivery.'.$key.'.product_id']   = 'This Field Is Required';
                $this->rules ['production_delivery.'.$key.'.qty']            = ['required'];
                $this->messages['production_delivery.'.$key.'.qty']          = 'This Field Is Required';
                $this->rules ['production_delivery.'.$key.'.scale']          = ['required'];
                $this->messages['production_delivery.'.$key.'.scale']        = 'This Field Is Required';
                $this->rules ['production_delivery.'.$key.'.del_qty']        = ['required'];
                $this->messages['production_delivery.'.$key.'.del_qty']      = 'This Field Is Required';
                if(!empty(request()->production_delivery[$key]['use_warehouse_id'])){
                    $this->rules ['production_delivery.'.$key.'.use_product_id']     = ['required'];
                    $this->messages['production_delivery.'.$key.'.use_product_id']   = 'This Field Is Required';
                    $this->rules ['production_delivery.'.$key.'.use_qty']            = ['required'];
                    $this->messages['production_delivery.'.$key.'.use_qty']          = 'This Field Is Required';
                }
                if(!empty(request()->production_delivery[$key]['merge_warehouse_id'])){
                    $this->rules ['production_delivery.'.$key.'.merge_product_id']       = ['required'];
                    $this->messages['production_delivery.'.$key.'.merge_product_id']     = 'This Field Is Required';
                    $this->rules ['production_delivery.'.$key.'.merge_qty']              = ['required'];
                    $this->messages['production_delivery.'.$key.'.merge_qty']            = 'This Field Is Required';
                    $this->rules ['production_delivery.'.$key.'.merge_price']            = ['required'];
                    $this->messages['production_delivery.'.$key.'.merge_price']          = 'This Field Is Required';
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
