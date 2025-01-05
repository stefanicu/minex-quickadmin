<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('brand_edit');
    }
    
    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:brands,name,'.request()->route('brand')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:brands,slug,'.request()->route('brand')->id,
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
