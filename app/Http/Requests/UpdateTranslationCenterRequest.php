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
            'language' => [
                'required',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:translation_centers,name,' . request()->route('translation_center')->id,
                Rule::unique('translation_center_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('translation_centers')->id,'translation_center_id')
            ]
        ];
    }
}
