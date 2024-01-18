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
            'language' => [
                'required',
            ],
            'brand_id' => [
                'required',
                'integer',
            ],
            'categories.*' => [
                'integer',
            ],
            'categories' => [
                'required',
                'array',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:products',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:products',
            ],
            'photo' => [
                'array',
            ],
        ];
    }
}
