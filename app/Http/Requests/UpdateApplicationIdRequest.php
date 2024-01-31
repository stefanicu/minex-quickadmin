<?php

namespace App\Http\Requests;

use App\Models\ApplicationId;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApplicationIdRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_id_edit');
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
                'unique:application_ids,name,' . request()->route('application_id')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:application_ids,slug,' . request()->route('application_id')->id,
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
