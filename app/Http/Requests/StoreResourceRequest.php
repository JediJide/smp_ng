<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('resource_create');
    }

    public function rules(): array
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
