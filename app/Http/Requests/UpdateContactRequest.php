<?php

namespace App\Http\Requests;

use App\Models\Contact;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateContactRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('contact_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:0',
                'max:100',
                'required',
            ],
            'surname' => [
                'string',
                'min:0',
                'max:100',
                'required',
            ],
            'email' => [
                'required',
            ],
            'job' => [
                'string',
                'min:0',
                'max:100',
                'required',
            ],
            'industry' => [
                'string',
                'min:0',
                'max:100',
                'required',
            ],
            'how_about' => [
                'string',
                'min:0',
                'max:150',
                'required',
            ],
            'message' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'company' => [
                'string',
                'min:0',
                'max:100',
                'nullable',
            ],
            'phone' => [
                'string',
                'min:0',
                'max:50',
                'required',
            ],
            'country' => [
                'string',
                'min:0',
                'max:100',
                'required',
            ],
            'county' => [
                'string',
                'min:0',
                'max:100',
                'required',
            ],
            'city' => [
                'string',
                'min:0',
                'max:100',
                'nullable',
            ],
            'checkbox' => [
                'string',
                'nullable',
            ],
            'product' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
