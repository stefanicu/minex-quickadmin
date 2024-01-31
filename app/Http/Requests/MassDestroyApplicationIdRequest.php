<?php

namespace App\Http\Requests;

use App\Models\ApplicationId;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyApplicationIdRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_id_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:application_ids,id',
        ];
    }
}
