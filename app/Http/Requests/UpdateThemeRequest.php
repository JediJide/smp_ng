<?php

namespace App\Http\Requests;

use App\Models\Theme;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Schema(),
 * required={"email", "password"}
 */
class UpdateThemeRequest extends FormRequest
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="name", default="Theme Name", description="name", property="name"),
     * @OA\Property(format="string", title="description", default="Theme description body", description="description", property="description"),
     * @OA\Property(format="int64", title="category_id", default="1", description="category_id", property="category_id"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     */
    public function authorize()
    {
        return Gate::allows('theme_edit');
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
