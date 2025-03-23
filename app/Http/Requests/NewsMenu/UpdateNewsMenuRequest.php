<?php

namespace App\Http\Requests\NewsMenu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class UpdateNewsMenuRequest extends FormRequest
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
        // Fetch all route parameters for debugging
        $routeParameters = $this->route()->parameters();
        \Log::info('Route Parameters:', $routeParameters);

        // Retrieve the specific route parameter 'news_menu'
        $newsMenu = $this->route('news_menu'); // This retrieves the model instance

        // Extract the ID from the model instance
        $id = $newsMenu ? $newsMenu->id : null;

        \Log::info('Updating NewsMenu with ID:', ['id' => $id]);

        return [
            'name' => 'sometimes|required|unique:news_menus,name,' . $id,
            'slug' => 'required',
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
