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
        return Gate::allows('front_page_create');
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
                'unique:front_pages',
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
