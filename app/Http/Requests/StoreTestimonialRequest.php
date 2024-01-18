<?php

namespace App\Http\Requests;

use App\Models\Testimonial;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('testimonial_create');
    }

    public function rules()
    {
        return [
            'language' => [
                'required',
            ],
            'company' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'content' => [
                'required',
            ],
            'name' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'job' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'logo' => [
                'array',
                'required',
            ],
            'logo.*' => [
                'required',
            ],
        ];
    }
}
