<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
        return $this->getCustomRules($this->input('type'));
    }

    public function getCustomRules($type)
    {
        $rules = [];
        switch($type) {
            case "state" :
                $id = $this->state->id ?? '';
                $table = 'states';
                break;
            case "city" :
                $id = $this->city->id ?? '';
                $table = 'cities';
                break;
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            return [
                'name' => "required|string|max:50|unique:{$table},name,{$id}",
            ];
        }
        return [
            'name' => "required|string|max:50|unique:{$table},name",
        ];

        return $rules;
    }

}
