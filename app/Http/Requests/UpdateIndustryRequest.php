<?php

namespace App\Http\Requests;

use App\Models\Industry;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateIndustryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('industry_edit');
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
                'unique:industries,name,' . request()->route('industry')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:industries,slug,' . request()->route('industry')->id,
            ],
        ];
    }
}
