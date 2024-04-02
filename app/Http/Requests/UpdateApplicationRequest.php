<?php

namespace App\Http\Requests;

use App\Models\Application;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
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
                    ->ignore(request()->route('application')->id,'application_id')
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                Rule::unique('application_translations', 'slug')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('application')->id,'application_id')
            ]
        ];
    }
}
