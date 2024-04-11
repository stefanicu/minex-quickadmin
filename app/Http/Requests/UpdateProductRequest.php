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
            'brand_id' => [
                'required',
                'integer',
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
            'applicaitons.*' => [
                'integer',
            ],
            'applicaitons' => [
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
