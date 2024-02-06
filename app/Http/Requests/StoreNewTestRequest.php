<?php

namespace App\Http\Requests;

use App\Models\NewTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreNewTestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('new_test_create');
    }

    public function rules()
    {
        return [
            'nume' => [
                'string',
                'nullable',
            ],
        ];
    }
}
