<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreAudienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('audience_create');
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
