<?php

namespace App\Http\Requests;

use App\Models\FrontPage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateFrontPageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('front_page_edit');
    }

    public function rules()
    {
        return [
            'locale' => [
                'required',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                Rule::unique('front_page_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('front_page')->id,'front_page_id')
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
