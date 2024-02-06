<?php

namespace App\Http\Requests;

use App\Models\Application;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_edit');
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
                'unique:applications,name,' . request()->route('application')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:applications,slug,' . request()->route('application')->id,
            ],
            'categories.*' => [
                'integer',
            ],
            'categories' => [
                'array',
            ],
            'test' => [
                'string',
                'nullable',
            ],
        ];
    }
}
