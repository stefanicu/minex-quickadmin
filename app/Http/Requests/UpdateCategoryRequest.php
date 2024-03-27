<?php

namespace App\Http\Requests;

use App\Models\Category;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_edit');
    }

    public function rules()
    {
        return [
            // 'locale' => [
            //     'required',
            // ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:category_translations,name,' . request()->route('category')->id . ',"category_id"',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:category_translations,slug,' . request()->route('category')->id . '"category_id"',
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
        ];
    }
}
