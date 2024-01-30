<?php

namespace App\Http\Requests;

use App\Models\Brand;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBrandRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('brand_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:brands,name,' . request()->route('brand')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:brands,slug,' . request()->route('brand')->id,
            ],
            'photo' => [
                'required',
            ],
        ];
    }
}
