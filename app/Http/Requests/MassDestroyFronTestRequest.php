<?php

namespace App\Http\Requests;

use App\Models\FronTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyFronTestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('fron_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:fron_tests,id',
        ];
    }
}
