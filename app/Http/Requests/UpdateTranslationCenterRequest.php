<?php

namespace App\Http\Requests;

use App\Models\TranslationCenter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateTranslationCenterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('translation_center_edit');
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
                Rule::unique('translation_center_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('translation_center')->id,'translation_center_id')
            ]
        ];
    }
}
