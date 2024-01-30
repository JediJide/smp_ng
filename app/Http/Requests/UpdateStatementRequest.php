<?php

namespace App\Http\Requests;

use App\Models\Statement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Schema(),
 * required={"title"}
 *
 * UpdateStatementRequest Class
 */
class UpdateStatementRequest extends FormRequest
{
    /**
     * @OA\Property(property="title", type="string", format="title", example="Pillar 1: Gene therapy overview", description="title"),
     * @OA\Property(property="description", type="string", format="description", example="<p>Strategic objective: Provide an overview of gene therapy and its potential benefits</p><p><strong>Core statement:</strong> Gene therapy is an innovative transformative treatment that modifies a person's genes to treat or cure a disease, with several agents already approved for use.</p>"),
     * @OA\Property(format="int64", default="4,5", description="integer list of resources Id(s)", property="resource_id"),
     * @OA\Property(format="string", default="1,2,3", description="integer list of references Id(s)", property="reference_id"),
     */
    public function authorize()
    {
        return Gate::allows('statement_edit');
    }

    public function rules()
    {
        return [
            'therapy_area_id' => [
                'required',
                'integer',
            ],
            'theme_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'string',
                'required',
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
            'order_by' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
