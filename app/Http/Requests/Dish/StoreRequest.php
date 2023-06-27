<?php

namespace App\Http\Requests\Dish;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|unique:dishes,name',
            'dish_picture' => 'nullable|image',
            'composition' => 'string',
            'calories' => 'required|integer',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ];
    }
}
