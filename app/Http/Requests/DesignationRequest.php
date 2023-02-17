<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
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
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            return [
                'name' => "required|string|max:50|unique:designations,name,{$this->designation->id}",
            ];
        }else{
            return [
                'name' => "required|string|max:50|unique:designations,name",
            ];
        }
    }
}
