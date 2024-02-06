<?php

namespace App\Http\Requests;

use App\Models\NewTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNewTestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('new_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:new_tests,id',
        ];
    }
}
