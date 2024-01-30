<?php

namespace App\Http\Requests;

use App\Models\StatementStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

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
