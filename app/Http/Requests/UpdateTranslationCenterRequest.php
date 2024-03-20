<?php

namespace App\Http\Requests;

use App\Models\TranslationCenter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTranslationCenterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('translation_center_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:translation_centers,name,' . request()->route('translation_center')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:translation_centers,slug,' . request()->route('translation_center')->id,
            ],
            'section' => [
                'required',
            ],
        ];
    }
}
