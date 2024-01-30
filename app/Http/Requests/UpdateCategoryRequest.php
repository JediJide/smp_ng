<?php

namespace App\Http\Requests;

/**
 * @OA\Schema(),
 * required={"email", "password"}
 */
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * @OA\Property(property="name", type="string", format="name", example="name"),
     * @OA\Property(property="therapy_area_id", type="int64", format="therapy_area_id", example="1"),
     */
    public function authorize(): bool
    {
        return Gate::allows('category_edit');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
            'therapy_area_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
