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
            'locale' => [
                'required',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:category_translations',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:category_translations',
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
