<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreLexiconRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('lexicon_create');
    }

    public function rules(): array
    {
        return [
            'preferred_phrase' => 'required',
            'guidance_for_usage' => 'nullable',
            'non_preferred_terms' => 'nullable',
            'therapy_area_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
