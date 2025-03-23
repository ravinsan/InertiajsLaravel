<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class UserRequest extends FormRequest
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
            'name'     =>'required',
            'email'    =>'required|email|regex:/(.*)\./i|unique:users,email,',
            'mobile'    =>'required|numeric|digits:10',
            'password' =>'required|min:6|same:password_confirmation',
            'role_id'  =>'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'role_id.required'    => 'Please Select a Role',
        ];
    }

    
}
