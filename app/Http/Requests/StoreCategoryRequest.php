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
            'cover_photo' => [
                'required',
            ],
            'product_image_id' => [
                'required',
                'integer',
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
