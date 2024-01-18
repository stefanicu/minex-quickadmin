<?php

namespace App\Http\Requests;

use App\Models\Home;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateHomeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('home_edit');
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
                'unique:homes,name,' . request()->route('home')->id,
            ],
            'slug' => [
                'string',
                'min:0',
                'max:255',
                'required',
                'unique:homes,slug,' . request()->route('home')->id,
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
