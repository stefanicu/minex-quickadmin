<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_create');
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
                'max:255',
            ],
            'application_id' => [
                'integer',
            ],
            'filters.*' => [
                'integer',
            ],
            'filters' => [
                'array',
            ],
        ];
    }
}
