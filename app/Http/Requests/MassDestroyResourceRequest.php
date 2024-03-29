<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(),
 * required={"id"}
 * MassDestroyResourceRequest class
 */
class MassDestroyResourceRequest extends FormRequest
{
    /**
     * @OA\Property(format="string", title="message", default="Resource has been deleted", property="message"),
     */
    public function authorize(): bool
    {
        abort_if(Gate::denies('resource_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'exists:resources,id',
        ];
    }
}
