<?php

namespace Modules\Loan\Http\Requests;

use App\Http\Requests\FormRequest;

class LoanCategoriesFormRequest extends FormRequest{
    public function rules(){
        return [
            //
        ];
    }
    public function authorize(){
        return true;
    }
}
