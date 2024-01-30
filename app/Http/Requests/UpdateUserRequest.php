<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Schema(),
 * required={"email", "password"}
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * @OA\Property(property="name", type="string", format="name", example="name"),
     * @OA\Property(property="last_name", type="string", format="last_name", example="lastname"),
     * @OA\Property(format="string", default="admin@synaptikdigital.com", description="email", property="email"),
     * @OA\Property(format="string", default="password", description="password", property="password"),
     * @OA\Property(property="roles", type="numeric", format="roles", example={{ "role_id": "1" }}),
     */
    public function authorize()
    {
        return Gate::allows('user_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'last_name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users,email,'.request()->route('user')->id,
            ],
            'roles' => [
                'required',
                'array',
            ],
        ];
    }
}
