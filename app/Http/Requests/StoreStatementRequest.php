<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreStatementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('statement_create');
    }

    public function rules(): array
    {
        return [
            'therapy_area_id' => [
                'required',
                'integer',
            ],
            'theme_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'string',
                'required',
            ],
            'resources.*' => [
                'integer',
            ],
            'resources' => [
                'array',
            ],
            'references.*' => [
                'integer',
            ],
            'references' => [
                'array',
            ],
            'order_by' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
