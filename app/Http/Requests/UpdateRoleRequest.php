<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(),
 * required={"SearchTerm"}
 *
 * UpdateRoleRequest Class
 */
class UpdateRoleRequest extends FormRequest
{
    /**
     * @OA\Property(property="category_id", type="string", format="title", example="1", description="category_id"),
     * @OA\Property(property="StatementSearch", type="string", format="description", example="Gene therapy"),
     * @OA\Property(property="NarrativeSearch", type="string", format="description", example="Gene therapy"),
     * @OA\Property(property="GlossarySearch", type="string", format="description", example="Gene therapy"),
     * @OA\Property(property="LexiconSearch", type="string", format="description", example="blah"),
     */

    //{"AssetId":"5a6ac412-e6a4-45c4-b925-e793df373ce2","SearchTerm":"p","StatementSearch":true,"NarrativeSearch":true}
    public function authorize()
    {
        return Gate::allows('role_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'permissions.*' => [
                'integer',
            ],
            'permissions' => [
                'required',
                'array',
            ],
        ];
    }
}
