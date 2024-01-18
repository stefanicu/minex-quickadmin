<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_edit');
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
                'unique:products,name,' . request()->route('product')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:products,slug,' . request()->route('product')->id,
            ],
            'photo' => [
                'array',
            ],
        ];
    }
}
