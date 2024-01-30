<?php

namespace App\Http\Requests;

use App\Models\TherapyArea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StoreTherapyAreaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('therapy_area_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
