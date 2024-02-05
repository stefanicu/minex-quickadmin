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
            'categories.*' => [
                'integer',
            ],
            'categories' => [
                'array',
            ],
        ];
    }
}
