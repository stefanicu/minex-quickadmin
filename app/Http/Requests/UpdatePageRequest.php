<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('page_edit');
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
                Rule::unique('page_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('page')->id, 'page_id')
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                Rule::unique('page_translations', 'slug')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('page')->id, 'page_id')
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
