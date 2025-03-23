<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class UpdateCategoryRequest extends FormRequest
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
        $id = $this->route('category');
        return [
            'name' => 'sometimes|required|unique:categories,name,' . $id . ',id',
            'slug'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please Enter Category Name',
            'name.unique' => 'Category Name Already Exists',
        ];
    }

    
}
