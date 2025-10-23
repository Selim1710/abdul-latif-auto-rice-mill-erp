<?php

namespace Modules\Sale\Http\Requests;

use App\Http\Requests\FormRequest;

class SaleFormRequest extends FormRequest {

    protected $rules    = [];
    protected $messages = [];

    public function rules(){

        $this->rules['invoice_no']              = ['required','unique:sales,invoice_no,'];
        $this->rules['sale_date']               = ['required'];
        $this->rules['sale_type']               = ['required'];
        $this->rules['party_type']              = ['required'];
        $this->rules['sale_status']             = ['required'];
        $this->rules['payment_status']          = ['required'];
        $this->rules['total_sale_sub_total']    = ['required'];
        $this->rules['total_sale_qty']          = ['required'];

        if(request()->party_type == 1){

            $this->rules['party_id']       = ['required'];
        }

        if(request()->party_type == 2){

            $this->rules['party_name']     = ['required'];

        }

        if(request()->payment_status == 1 || request()->payment_status == 2){

            $this->rules['payment_method']     = ['required'];
            $this->rules['account_id']         = ['required'];

        }

        if(request()->update_id){

            $this->rules['invoice_no'][1]      = 'unique:sales,invoice_no,'.request()->update_id;

        }

        if(request()->has('sale')){

            foreach (request()->sale as $key => $value){

                $this->rules ['sale.'.$key.'.warehouse_id']                 = ['required'];
                $this->rules ['sale.'.$key.'.product_id']                   = ['required'];
                $this->rules ['sale.'.$key.'.qty']                          = ['required'];
                $this->rules ['sale.'.$key.'.scale']                        = ['required'];
                $this->rules ['sale.'.$key.'.sel_qty']                      = ['required'];
                $this->rules ['sale.'.$key.'.price']                        = ['required'];

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

}
