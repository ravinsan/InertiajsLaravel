<?php

namespace App\Http\Requests\Region;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class CreateRegionRequest extends FormRequest
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
            'name' =>'required|unique:regions,name',
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
