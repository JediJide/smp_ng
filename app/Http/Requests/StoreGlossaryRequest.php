<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreGlossaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('glossary_create');
    }

    public function rules(): array
    {
        return [
            'term' => ['string|required'],
            'definition' => ['string|nullable'],
            'therapy_area_id' => ['required', 'integer'],
        ];
    }
}
