<?php

namespace App\Http\Requests\Region;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class UpdateRegionRequest extends FormRequest
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
        $id = $this->route('region');
        return [
            'name' => 'sometimes|required|unique:regions,name,' . $id . ',id',
            'slug'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Enter Region Name',
            'name.unique' => 'Region Name Already Exists',
        ];
    }

    
}
