<?php

namespace App\Http\Requests;

use App\Models\FrontPage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFrontPageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('frontpage_create');
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
                'unique:frontpages',
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
