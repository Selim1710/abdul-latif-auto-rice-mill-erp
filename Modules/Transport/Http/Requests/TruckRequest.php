<?php

namespace Modules\Transport\Http\Requests;

use App\Http\Requests\FormRequest;

class TruckRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['truck_no']   = ['required','string','unique:trucks,truck_no'];
        $rules['asset_price'] = ['required'];
        if(request()->update_id)
        {
            $rules['truck_no'][2] = 'unique:trucks,truck_no,'.request()->update_id;
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
