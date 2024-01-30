<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ThemeResource
 *
 * @OA\Schema(
 * )
 */
class ThemeResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="name", default="Category Name", description="name", property="name"),
     * @OA\Property(format="string", title="description", default="Theme description body", description="description", property="description"),
     * @OA\Property(format="int64", title="category_id", default="1", description="category_id", property="category_id"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
