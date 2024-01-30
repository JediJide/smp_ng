<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class StatementResource
 *
 * @OA\Schema(
 * )
 */
class StatementResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="title", default="Pillar 1: Gene therapy overview", description="title", property="title"),
     * @OA\Property(format="string", title="description", default="<p>Strategic objective: Provide an overview of gene therapy and its potential benefits</p><p><strong>Core statement:</strong> Gene therapy is an innovative transformative treatment that modifies a person's genes to treat or cure a disease, with several agents already approved for use.</p>", description="description", property="description"),
     * @OA\Property(format="int64", title="theme_id", default="12", description="theme_id", property="theme_id"),
     * @OA\Property(format="int64", title="parent_id", default="12", description="parent_id", property="parent_id"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
