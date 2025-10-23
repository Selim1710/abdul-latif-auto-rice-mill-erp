<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;


class CategoryFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules['name'] = ['required','string','unique:categories,name'];
        if(request()->update_id)
        {
            $rules['name'][2] = 'unique:categories,name,'.request()->update_id;
        }

        return $rules;
    }
}
