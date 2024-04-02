<?php

namespace App\Http\Requests;

use App\Models\FrontPage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFrontPageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('frontpage_edit');
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
                'unique:frontpages,name,' . request()->route('frontpage')->id,
            ],
            'button' => [
                'string',
                'min:0',
                'max:255',
                'nullable',
            ],
        ];
    }
}
