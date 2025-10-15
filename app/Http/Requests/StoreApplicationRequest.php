<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_create');
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
                'unique:application_translations',
            ],
            'slug' => [
                'max:255',
            ],
            'title' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'subtitle' => [
                'min:0',
                'max:255',
            ],
            'categories.*' => [
                'integer',
            ],
            'categories' => [
                'array',
            ],
        ];
    }
}
