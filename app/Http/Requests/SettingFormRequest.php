<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class SettingFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => 'required|string',
            'email'             => 'nullable|email|string',
            'contact_no'        => 'required|string',
            'address'           => 'required|string',
            'currency_code'     => 'required|string',
            'currency_symbol'   => 'required|string',
            'currency_position' => 'required|integer',
            // 'tax'               => 'numeric',
            // 'tax_option'        => 'integer',
            // 'vat'               => 'numeric',
            // 'vat_option'        => 'integer',
            'timezone'          => 'string',
            'date_format'       => 'string',
            'invoice_prefix'    => 'required|string',
            'invoice_number'    => 'required|string',
            'logo'              => 'nullable|image|mimes:svg,png,jpg,jpeg',
            'favicon'           => 'nullable|image|mimes:png',
        ];
    }
}
