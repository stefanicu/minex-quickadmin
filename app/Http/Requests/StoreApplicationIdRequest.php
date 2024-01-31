<?php

namespace App\Http\Requests;

use App\Models\ApplicationId;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreApplicationIdRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_id_create');
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
                'unique:application_ids',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:application_ids',
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
