<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreThemeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('theme_create');
    }

    public function rules()
    {
        return [
            'therapy_area_id' => [
                'required',
                'integer',
            ],
            'category_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'nullable',
            ],
            'resources.*' => [
                'integer',
            ],
            'resources' => [
                'array',
            ],
            'references.*' => [
                'integer',
            ],
            'references' => [
                'array',
            ],
        ];
    }
}
