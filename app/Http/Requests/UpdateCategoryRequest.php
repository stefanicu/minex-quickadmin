<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_edit');
    }
    
    public function rules(): array
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
                Rule::unique('category_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('category')->id, 'category_id')
            
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                Rule::unique('category_translations', 'slug')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('category')->id, 'category_id')
            
            ],
            'page_views' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'applications.*' => [
                'integer',
            ],
            'applications' => [
                'array',
            ],
            'meta_title' => [
                'string',
                'min:0',
                'max:255',
                'nullable'
            ],
            'meta_description' => [
                'string',
                'min:0',
                'max:255',
                'nullable'
            ],
            'author' => [
                'string',
                'min:0',
                'max:255',
                'nullable'
            ],
            'robots' => [
                'string',
                'min:0',
                'max:255',
                'nullable'
            ],
            'canonical_url' => [
                'string',
                'min:0',
                'max:255',
                'nullable'
            ]
        ];
    }
}
