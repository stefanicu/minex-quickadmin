<?php

namespace App\Http\Requests;

use App\Models\Reference;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreReferenceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('reference_create');
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
                'unique:references',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:references',
            ],
            'content' => [
                'required',
            ],
            'photo_square' => [
                'array',
                'required',
            ],
            'photo_square.*' => [
                'required',
            ],
            'photo_wide' => [
                'required',
            ],
        ];
    }
}
