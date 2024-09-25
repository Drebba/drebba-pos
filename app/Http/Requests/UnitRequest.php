<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
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
        $rules = [
            'unit.title' => ['required','string','max:255','unique:units,title'],
        ];

        if ($this->getMethod() == 'PATCH') {
            $rules['unit.title'] = ['required', 'string','max:255', Rule::unique('units', 'title')->ignore($this->route('unit'))];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'unit.title.required' => 'Unit name is required',
            'unit.title.unique' => 'Unit name is already exists',
        ];
    }
}
