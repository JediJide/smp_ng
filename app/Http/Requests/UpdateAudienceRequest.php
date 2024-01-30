<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAudienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('audience_edit');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
