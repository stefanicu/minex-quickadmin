<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('testimonial_create');
    }
    
    public function rules()
    {
        return [
            'locale' => [
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
                'nullable',
            ],
            'job' => [
                'string',
                'min:0',
                'max:255',
                'nullable',
            ]
        ];
    }
}
