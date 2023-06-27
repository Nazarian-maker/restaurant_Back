<?php

namespace App\Http\Requests\Dish;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'name' => 'string',
//            'composition' => 'string',
            'price' => 'integer',
            'calories' => 'integer',
            'sort' => 'nullable|string|in:asc,desc',
            'sort_By' => 'nullable|string|in:name,calories,price',
        ];
    }
}
