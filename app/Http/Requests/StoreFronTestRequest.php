<?php

namespace App\Http\Requests;

use App\Models\FronTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFronTestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('fron_test_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
