<?php

namespace App\Http\Requests;

use App\Models\Theme;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

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
