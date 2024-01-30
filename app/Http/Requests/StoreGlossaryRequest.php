<?php

namespace App\Http\Requests;

use App\Models\Glossary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StoreGlossaryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('glossary_create');
    }

    public function rules()
    {
        return [
            'term' => ['string|required'],
            'definition' => ['string|nullable'],
            'therapy_area_id' => ['required', 'integer'],
        ];
    }
}
