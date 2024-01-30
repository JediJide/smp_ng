<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreStatementStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('statement_status_create');
    }

    public function rules(): array
    {
        return [
            'status' => [
                'string',
                'required',
            ],
        ];
    }
}
