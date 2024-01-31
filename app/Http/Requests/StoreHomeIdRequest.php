<?php

namespace App\Http\Requests;

use App\Models\HomeId;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreHomeIdRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('home_id_create');
    }

    public function rules()
    {
        return [];
    }
}
