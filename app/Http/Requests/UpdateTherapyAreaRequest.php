<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Schema(),
 * required={"name"}
 */
class UpdateTherapyAreaRequest extends FormRequest
{
    /**
     * @OA\Property(property="name", type="string", format="name", example="name")
     */
    public function authorize(): bool
    {
        return Gate::allows('therapy_area_edit');
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
