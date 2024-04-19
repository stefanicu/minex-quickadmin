<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReferenceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('reference_edit');
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
                Rule::unique('reference_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('reference')->id,'reference_id')
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                Rule::unique('reference_translations', 'slug')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('reference')->id,'reference_id')
            ]
        ];
    }
}
