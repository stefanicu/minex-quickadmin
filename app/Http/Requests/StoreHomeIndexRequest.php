<?php

namespace App\Http\Requests;

use App\Models\HomeIndex;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreHomeIndexRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('home_index_create');
    }

    public function rules()
    {
        return [];
    }
}
