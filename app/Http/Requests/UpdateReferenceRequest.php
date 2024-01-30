<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('reference_edit');
    }

    public function rules(): array
    {
        return [
            'url' => [
                'string',
                'nullable',
            ],
            'tag' => [
                'string',
                'nullable',
            ],
            'file' => [
                'array',
            ],
            'title' => [
                'string',
                'required',
            ],
        ];
    }
}
