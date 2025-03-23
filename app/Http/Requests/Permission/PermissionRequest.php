<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class PermissionRequest extends FormRequest
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
            'name'        =>'required',
            'menu_id'     =>'required',
            'url'         =>'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'    => 'Please Enter Permission Name.',
            'menu_id.required' => 'Please Select a Parent Menu',
            'sub_menu_id.required'  => 'Please Select a Sub Menu',
            'url.required'          => 'Please Enter Route Name',
        ];
    }

    
}
