<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Schema(),
 * required={"preferred_phrase"}
 */
class UpdateLexiconRequest extends FormRequest
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="preferred_phrase", default="preferred_phrase", description="preferred_phrase", property="preferred_phrase"),
     * @OA\Property(format="string", title="guidance_for_usage", default="guidance_for_usage", description="guidance_for_usage", property="guidance_for_usage"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     */
    public function authorize(): bool
    {
        return Gate::allows('lexicon_edit');
    }

    public function rules(): array
    {
        return [
            'preferred_phrase' => 'string|required',
            'guidance_for_usage' => 'string|nullable',
            'non_preferred_terms' => 'string|nullable',
            'therapy_area_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
