<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'first_name' => 'required|regex:/^[a-zA-Z\'.\s]*$/',
            'last_name' => 'required|regex:/^[a-zA-Z\'.\s]*$/',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'employee_code' => 'required|numeric|unique:users,employee_code',
            'designation' => 'required|numeric',
            'department' => 'required|numeric',
            'mobile_no' => 'required|digits:10',

        ];
    }
}
