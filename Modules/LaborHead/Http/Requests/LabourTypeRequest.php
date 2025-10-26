<?php

namespace Modules\LaborHead\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabourTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['name']      = ['required','string','unique:labour_types,name'];
        $rules['deletable'] = ['required'];
        if(request()->update_id)
        {
            $rules['name'] = 'unique:labour_types,name,'.request()->update_id;
        }
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

