<?php

namespace Modules\Asset\Http\Requests;

use App\Http\Requests\FormRequest;

class AssetTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['name'] = ['required','string','unique:asset_types,name'];
        if(request()->update_id)
        {
            $rules['name'][2] = 'unique:asset_types,name,'.request()->update_id;
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
