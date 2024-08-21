<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_edit');
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
                Rule::unique('product_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('product')->id,'product_id')
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                Rule::unique('product_translations', 'slug')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('product')->id,'product_id')
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
