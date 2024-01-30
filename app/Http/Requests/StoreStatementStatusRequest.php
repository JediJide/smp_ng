<?php

namespace App\Http\Requests;

use App\Models\StatementStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreStatementStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('statement_status_create');
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
