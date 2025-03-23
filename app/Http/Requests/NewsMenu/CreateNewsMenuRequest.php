<?php

namespace App\Http\Requests\NewsMenu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class CreateNewsMenuRequest extends FormRequest
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
            'name' =>'required|unique:news_menus,name',
            'slug'  => 'required',
            
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Enter News Menu Name',
            'name.unique' => 'News Menu Name Already Exists',
        ];
    }

    
}
