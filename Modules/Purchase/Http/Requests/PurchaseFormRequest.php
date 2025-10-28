<?php

namespace Modules\Purchase\Http\Requests;

use App\Http\Requests\FormRequest;

class PurchaseFormRequest extends FormRequest {

    protected $rules    = [];
    protected $messages = [];

    public function rules() {

        $this->rules['invoice_no']                          = ['required','unique:purchases,invoice_no'];
        $this->rules['purchase_date']                       = ['required'];
        $this->rules['party_type']                          = ['required'];
        $this->rules['purchase_status']                     = ['required'];
        $this->rules['payment_status']                      = ['required'];
        $this->rules['total_purchase_sub_total']            = ['required'];
        $this->rules['total_purchase_qty']                  = ['required'];
        $this->rules['total_load_unload']                  = ['nullable'];

        if(request()->party_type == 1) {

            $this->rules['party_id']          = ['required'];

        }

        if(request()->party_type == 2) {

            $this->rules['party_name']        = ['required'];

        }

        if(request()->payment_status == 1 || request()->payment_status == 2) {

            $this->rules['payment_method']    = ['required'];
            $this->rules['account_id']        = ['required'];

        }

        if(request()->update_id) {

            $this->rules['invoice_no'][1]      = 'unique:purchases,invoice_no,'.request()->update_id;

        }
        if(request()->has('purchase')){

            foreach (request()->purchase as $key => $value){

                $this->rules ['purchase.'.$key.'.warehouse_id']  = ['required'];
                $this->rules ['purchase.'.$key.'.product_id']    = ['required'];
                $this->rules ['purchase.'.$key.'.qty']           = ['required'];
                $this->rules ['purchase.'.$key.'.scale']         = ['required'];
                $this->rules ['purchase.'.$key.'.rec_qty']       = ['required'];
                $this->rules ['purchase.'.$key.'.price']         = ['required'];

            }

        }

        return $this->rules;

    }

    public function authorize() {

        return true;

    }

    public function messages() {

        return $this->messages;

    }

}
