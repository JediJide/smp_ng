<?php

namespace App\Http\Requests;

use App\Models\Lexicon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StoreLexiconRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lexicon_create');
    }

    public function rules()
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
