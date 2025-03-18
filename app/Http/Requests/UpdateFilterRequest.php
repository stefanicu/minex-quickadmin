<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFilterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('filter_edit');
    }
    
    public function rules(): array
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
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'category_id' => [
                'integer',
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
