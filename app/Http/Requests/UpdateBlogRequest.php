<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('blog_edit');
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
                Rule::unique('blog_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('blog')->id, 'blog_id')
            ],
            'slug' => [
                'max:255',
                //                Rule::unique('blog_translations', 'slug')
                //                    ->where('locale', app()->getLocale())
                //                    ->ignore(request()->route('blog')->id, 'blog_id')
            ],
            'content' => [
                'nullable',
                'string'
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
