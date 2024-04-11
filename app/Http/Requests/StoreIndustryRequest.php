<?php

namespace App\Http\Requests;

use App\Models\Industry;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreIndustryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('industry_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:industries',
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:industries',
            ],
        ];
    }
}
