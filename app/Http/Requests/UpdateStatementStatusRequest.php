<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatementStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('statement_status_edit');
    }

    public function rules()
    {
        return [
            'status' => [
                'string',
                'required',
            ],
        ];
    }
}
