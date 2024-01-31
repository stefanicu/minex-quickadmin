<?php

namespace App\Http\Requests;

use App\Models\HomeId;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateHomeIdRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('home_id_edit');
    }

    public function rules()
    {
        return [];
    }
}
