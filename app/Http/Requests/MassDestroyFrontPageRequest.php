<?php

namespace App\Http\Requests;

use App\Models\FrontPage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyFrontPageRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('front_page_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:front_pages,id',
        ];
    }
}
