<?php

namespace App\Http\Requests;

use App\Models\HomeIndex;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateHomeIndexRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('home_index_edit');
    }

    public function rules()
    {
        return [];
    }
}
