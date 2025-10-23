<?php

namespace Modules\Expense\Http\Requests;

use App\Http\Requests\FormRequest;

class ExpenseItemFormRequest extends FormRequest{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $rules['name']              = ['required','string','unique:expense_items,name'];
        $rules['expense_type']      = ['required'];
        if(request()->update_id) {
            $rules['name']          = 'unique:expense_items,name,'.request()->update_id;
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
