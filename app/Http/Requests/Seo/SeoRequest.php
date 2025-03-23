<?php

namespace App\Http\Requests\Seo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class SeoRequest extends FormRequest
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
            'page_id'          => 'required',
            'meta_title'       => 'required',
            'meta_keyword'     => 'required',
            'meta_description' => 'required',
            
        ];
    }
    
    public function messages()
    {
        return [
            'page_id.required'          => 'Please Select a Page',
            'meta_title.required'       => 'Please Enter The Meta Title',
            'meta_keyword.required'     => 'Please Enter The Meta Keyword',
            'meta_description.required' => 'Please Enter The Meta Description',
        ];
    }

    
}
