<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatementStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('statement_status_edit');
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
