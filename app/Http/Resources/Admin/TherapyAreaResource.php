<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ThemeResource
 *
 * @OA\Schema(
 * )
 */
class TherapyAreaResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="name", default="Category Name", description="name", property="name"),
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
