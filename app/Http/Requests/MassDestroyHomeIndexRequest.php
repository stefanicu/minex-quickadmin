<?php

namespace App\Http\Requests;

use App\Models\HomeIndex;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyHomeIndexRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('home_index_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:home_indices,id',
        ];
    }
}
