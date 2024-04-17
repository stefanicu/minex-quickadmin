<?php

namespace App\Http\Requests;

use App\Models\Industry;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateIndustryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('industry_edit');
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
                Rule::unique('industry_translations', 'name')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('industry')->id,'industry_id')
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                Rule::unique('industry_translations', 'slug')
                    ->where('locale', app()->getLocale())
                    ->ignore(request()->route('industry')->id,'industry_id')
            ],
        ];
    }
}
