<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_create');
    }
    
    public function rules()
    {
        return [
            'locale' => [
                'required',
            ],
            'brand_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:product_translations',
            ],
            'slug' => [
                'max:255',
            ],
            'applications.*' => [
                'integer',
            ],
            'applications' => [
                'array',
            ],
            'categories.*' => [
                'integer',
            ],
            'categories' => [
                'array',
            ],
            'photo' => [
                'array',
            ],
            'references.*' => [
                'integer',
            ],
            'references' => [
                'array',
            ],
        ];
    }
}
