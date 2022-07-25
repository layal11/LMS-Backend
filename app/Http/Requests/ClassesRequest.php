<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassesRequest extends FormRequest
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
            'name' => 'required|unique:classes|max:20', //table classes
        ];
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'name' => 'Class Name',
        ];
    }

    /**
     * @return array|void
     */
    public function messages()
    {
        return [
            'required' => ':attribute is required',
//            'size' => 'The :attribute must be exactly :size.',
        ];
    }
}
