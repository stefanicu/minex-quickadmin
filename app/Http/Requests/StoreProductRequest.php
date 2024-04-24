<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

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
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:product_translations',
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
