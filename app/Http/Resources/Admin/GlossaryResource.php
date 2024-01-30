<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CategoryResource
 * @OA\Schema(
 * )
 */
class GlossaryResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="name", default="term", description="name", property="name"),
     * @OA\Property(format="string", title="definition", default="term", description="definition", property="definition"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     * @OA\Property(format="string", title="created_at", default="2022-01-06 15:47:41", description="created_at", property="created_at"),
     * @OA\Property(format="string", title="updated_at", default="2022-01-06 15:47:41", description="updated_at", property="updated_at"),
     * @OA\Property(format="string", title="deleted_at", default="2022-01-06 15:47:41", description="deleted_at", property="deleted_at"),
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
