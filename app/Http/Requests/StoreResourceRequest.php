<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('resource_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
            'filename' => [
                'array',
                'required',
            ],
            'filename.*' => [
                'required',
            ],
            'url' => [
                'string',
                'nullable',
            ],
            'temporary_url' => [
                'string',
                'nullable',
            ],
        ];
    }
}
