<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreTherapyAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('therapy_area_create');
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
