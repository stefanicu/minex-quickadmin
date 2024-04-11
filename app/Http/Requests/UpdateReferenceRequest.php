<?php

namespace App\Http\Requests;

use App\Models\Reference;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReferenceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('reference_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:references,name,' . request()->route('reference')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:references,slug,' . request()->route('reference')->id,
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
