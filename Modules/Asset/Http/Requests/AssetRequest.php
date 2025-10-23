<?php

namespace Modules\Asset\Http\Requests;

use App\Http\Requests\FormRequest;

class AssetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['tag']           = ['required','string','unique:assets,tag'];
        $rules['name']          = ['required','string'];
        $rules['asset_type_id'] = ['required'];
        $rules['cost']          = ['required','numeric','gt:0'];
        $rules['purchase_date'] = ['nullable','date','date_format:Y-m-d'];
        $rules['warranty']      = ['nullable','numeric','gte:0'];
        $rules['user']          = ['nullable'];
        $rules['location']      = ['nullable'];
        $rules['asset_status']  = ['required'];
        $rules['description']   = ['nullable'];
        $rules['photo']         = ['nullable','image','mimes:png,jpg,jpeg'];
        if(request()->update_id)
        {
            $rules['tag'][2] = 'unique:assets,tag,'.request()->update_id;
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
