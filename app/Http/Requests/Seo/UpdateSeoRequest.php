<?php

namespace App\Http\Requests\Seo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class UpdateSeoRequest extends FormRequest
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
            'title'    => 'required',
           
        ];
    }
    
    // public function messages()
    // {
    //     return [
    //         'category_id.required'    => 'Please Select a Category',
    //         'subcategory_id.required' => 'Please Select a Subcategory',
    //         'document_name.required'  => 'Enter Document Name',
    //     ];
    // }

    
}
