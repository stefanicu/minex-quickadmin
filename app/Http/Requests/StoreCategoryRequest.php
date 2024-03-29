<?php

namespace App\Http\Requests;

use App\Models\Category;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_create');
    }

    public function rules()
    {
        return [
            'language' => [
                'required',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:categories',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:categories',
            ],
            'applications.*' => [
                'integer',
            ],
            'applications' => [
                'array',
            ],
        ];
    }
}
