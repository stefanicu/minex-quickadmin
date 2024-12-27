<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('blog_create');
    }
    
    public function rules()
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
                'unique:blog_translations',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:blog_translations',
            ],
            'content' => [
                'nullable',
                'string'
            ],
        ];
    }
}
