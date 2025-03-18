<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreFilterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('filter_create');
    }
    
    public function rules()
    {
        return [
            'locale' => [
                'required',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'category_id' => [
                'integer',
            ],
        ];
    }
}
