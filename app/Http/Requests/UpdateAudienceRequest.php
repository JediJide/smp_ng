<?php

namespace App\Http\Requests;

use App\Models\Audience;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAudienceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('audience_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
