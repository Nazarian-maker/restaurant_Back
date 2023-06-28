<?php

namespace App\Http\Requests\Order;

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
            'number' => 'integer',
            'total_cost' => 'integer',
            'closed_at' => 'date',
            'is_closed' => ['boolean'],
            'user_id' => 'integer|exists:users,id',
            'sort' => 'nullable|string|in:asc,desc',
            'sort_By' => 'nullable|string|in:number,total_cost,closed_at,user_id',
        ];
    }
}
