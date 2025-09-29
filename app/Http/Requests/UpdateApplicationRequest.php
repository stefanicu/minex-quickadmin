<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_edit');
    }
    
    public function rules()
    {
        return [
            'categories.*' => [
                'integer',
            ],
            'categories' => [
                'array',
            ],
            'locale' => [
                'required',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                Rule::unique('application_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('application')->id, 'application_id')
            ],
            'slug' => [
                'max:255',
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
