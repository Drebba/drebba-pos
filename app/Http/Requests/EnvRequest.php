<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnvRequest extends FormRequest
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
            'DB_DATABASE' => ['required'],
            'DB_USERNAME' => ['required'],
            'DB_HOST' => ['required'],
            'DB_PORT' => ['required'],
        ];
    }

    public function messages()
    {
       return [
           'DB_DATABASE.required' => 'Database name is required',
           'DB_USERNAME.required' => 'Database user name is required',
           'DB_HOST.required' => 'Database host is required',
           'DB_PORT.required' => 'Database port is required',
       ];
    }
}
