<?php

namespace App\Http\Requests;

use App\Models\TranslationCenter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTranslationCenterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('translation_center_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:translation_centers,id',
        ];
    }
}
