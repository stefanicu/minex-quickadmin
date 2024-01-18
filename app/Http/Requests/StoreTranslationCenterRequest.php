<?php

namespace App\Http\Requests;

use App\Models\TranslationCenter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTranslationCenterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('translation_center_create');
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
                'unique:translation_centers',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:translation_centers',
            ],
            'section' => [
                'required',
            ],
        ];
    }
}
